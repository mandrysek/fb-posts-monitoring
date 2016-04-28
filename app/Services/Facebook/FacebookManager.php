<?php
namespace App\Services\Facebook;

use App\Models\FacebookPage;
use App\Models\ForbiddenPost;
use App\Models\User;
use Carbon\Carbon;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookRequest;

class FacebookManager
{
    private $id;
    private $secret;
    private $version;
    private $permissions;
    /**
     * @var Facebook
     */
    private $fb;

    public function __construct()
    {
        $this->id = env('FACEBOOK_ID', null);
        $this->secret = env('FACEBOOK_SECRET', null);
        $this->version = env('FACEBOOK_VERSION', 'v2.6');
        $this->permissions = explode(',', env('FACEBOOK_PERMISSIONS', ['email']));

        $this->fb = new Facebook([
            'app_id'                  => $this->id,
            'app_secret'              => $this->secret,
            'default_graph_version'   => $this->version,
            'http_client_handler'     => new GuzzleHttpClient(),
            'persistent_data_handler' => new PersistentDataHandler(),
        ]);
    }

    private function getAppAccessToken()
    {
        return $this->id.'|'.$this->secret;
    }

    public function getLoginUrl($callback)
    {
        $helper = $this->fb->getRedirectLoginHelper();

        return $helper->getLoginUrl($callback, $this->permissions);
    }

    /**
     * @return bool|AccessToken|null
     */
    public function handleCallback()
    {
        $helper = $this->fb->getRedirectLoginHelper();

        try
        {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e)
        {
            return false;
        } catch (FacebookSDKException $e)
        {
            return false;
        }

        $tokenMetadata = $this->getTokenMetadata($accessToken);

        if ($tokenMetadata === false)
        {
            return false;
        }

        if (!$accessToken->isLongLived())
        {
            try
            {
                $accessToken = $this->fb->getOAuth2Client()->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e)
            {
                return false;
            }
        }

        return $this->getUser($accessToken);
    }

    /**
     * @param $accessToken
     *
     * @return bool|\Facebook\Authentication\AccessTokenMetadata
     */
    public function getTokenMetadata($accessToken)
    {
        try
        {
            $oAuth2Client = $this->fb->getOAuth2Client();

            return $oAuth2Client->debugToken($accessToken);
        } catch (FacebookResponseException $e)
        {
            return false;
        } catch (FacebookSDKException $e)
        {
            return false;
        }
    }

    /**
     * @param $accessToken
     *
     * @return bool
     */
    public function isTokenValid($accessToken)
    {
        $tokenMetadata = $this->getTokenMetadata($accessToken);

        return $tokenMetadata !== false && $tokenMetadata->getIsValid() === true;
    }

    /**
     * @param $accessToken
     *
     * @return null|User
     */
    public function getUser($accessToken)
    {
        try
        {
            if (!($accessToken instanceof AccessToken))
            {
                $tokenMetadata = $this->getTokenMetadata($accessToken);
                $accessToken = new AccessToken($accessToken, $tokenMetadata->getExpiresAt());
            }

            $response = $this->fb->get('me?fields=id,name,email', $accessToken);
            $array = $response->getDecodedBody();
            $user = User::where('fb_id', $array['id'])->first();
            if (is_null($user))
            {
                $user = User::create([
                    'name'         => $array['name'],
                    'email'        => isset($array['email']) ? $array['email'] : null,
                    'fb_id'        => $array['id'],
                    'fb_token'     => $accessToken->getValue(),
                    'fb_token_exp' => $accessToken->getExpiresAt(),
                ]);
            }
            elseif (empty($user->fb_id) || empty($user->fb_token) || empty($user->fb_token_exp))
            {
                $user->update([
                    'fb_id'        => $array['id'],
                    'fb_token'     => $accessToken->getValue(),
                    'fb_token_exp' => $accessToken->getExpiresAt(),
                ]);
            }

            return $user;

        } catch (FacebookResponseException $e)
        {
            return null;
        } catch (FacebookSDKException $e)
        {
            return null;
        }
    }

    public function getFacebookPageInfo($pageId, $accessToken)
    {
        try
        {
            $response = $this->fb->get($pageId.'?fields=id,global_brand_page_name,name', $accessToken);
            $decoded = $response->getDecodedBody();

            return new FacebookPage([
                'fb_id' => $decoded['id'],
                'name'  => isset($decoded['global_brand_page_name']) ? $decoded['global_brand_page_name'] : $decoded['name'],
            ]);
        } catch (FacebookResponseException $e)
        {
            return null;
        } catch (FacebookSDKException $e)
        {
            return null;
        }
    }

    public function readFacebookPagesFromCsv($csvFilePath, $accessToken)
    {
        $facebookPagesRequests = [];
        $pageIdentifiers = [];

        if (($handle = fopen($csvFilePath, "r")) !== false)
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== false)
            {
                foreach ($data as $line)
                {
                    $links = explode(" ", $line);

                    foreach ($links as $link)
                    {
                        if (!empty($link))
                        {
                            $link = trim(preg_replace("/(https:\/\/www.facebook.com\/|(&|\?)\w+=\w+)/", "", urldecode($link)), "/");

                            $linkParts = explode("/", $link);
                            $pageIdentifier = explode("-", $linkParts[0] === "pages" ? $linkParts[2] : $linkParts[0]);
                            $count = count($pageIdentifier);
                            $pageIdentifier = is_numeric($pageIdentifier[$count - 1]) ? $pageIdentifier[$count - 1] : $pageIdentifier[0];

                            if (!in_array($pageIdentifier, $pageIdentifiers))
                            {
                                $facebookPagesRequests[] = $this->fb->request("GET", $pageIdentifier.'?fields=id,global_brand_page_name,name', [], $accessToken);
                                $pageIdentifiers[] = $pageIdentifier;
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }

        $requestsChunks = array_chunk($facebookPagesRequests, 50);

        try
        {
            $facebookPages = [];

            foreach ($requestsChunks as $requestsChunk)
            {
                $batchResponse = $this->fb->sendBatchRequest($requestsChunk, $accessToken);
                $responses = $batchResponse->getDecodedBody();
                foreach ($responses as $response)
                {
                    if ($response['code'] == 200)
                    {
                        $now = new Carbon();
                        $data = \GuzzleHttp\json_decode($response['body']);
                        $facebookPages[] = [
                            'fb_id'      => $data->id,
                            'name'       => $data->global_brand_page_name,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    else
                    {
                        return $response;
                    }
                }
            }

            return FacebookPage::insert($facebookPages);
        } catch (FacebookResponseException $e)
        {
            return ($e);
        } catch (FacebookSDKException $e)
        {
            return ($e);
        }
    }

    public function detectForbiddenPosts($bannedStrings, $accessToken)
    {
        $pagesChunks = FacebookPage::all()->chunk(50);

        foreach ($pagesChunks as $pagesChunk)
        {
            $requests = [];

            $pages = [];

            foreach ($pagesChunk as $page)
            {
                $pages[$page->fb_id] = $page->id;
                $lastPost = $page->forbiddenPosts()->withTrashed()->orderBy('created_time', 'desc')->first();

                if ($lastPost instanceof ForbiddenPost)
                {
                    $since = '&since='.$lastPost->created_time->getTimestamp();
                }
                else
                {
                    $since = null;
                }

                $requests[] = $this->fb->request("GET", $page->fb_id.'/posts?fields=message,permalink_url,created_time,from&limit=25' . $since, [], $accessToken);
            }

            try
            {
                $batchResponses = $this->fb->sendBatchRequest($requests, $accessToken);

                $forbiddenPosts = [];
                foreach ($batchResponses->getDecodedBody() as $batchResponse)
                {
                    $responseBody = \GuzzleHttp\json_decode($batchResponse['body'], true);

                    if ($batchResponse['code'] == 200)
                    {
                        $data = $responseBody['data'];

                        foreach ($data as $post)
                        {
                            $stringsFound = [];

                            if (isset($post['message']))
                            {
                                foreach ($bannedStrings as $bannedString)
                                {
                                    if (strpos($post['message'], $bannedString->value) !== false)
                                    {
                                        $stringsFound[] = $bannedString->value;
                                    }
                                }
                            }

                            $explodedId = explode('_', $post['id']);

                            if (!empty($stringsFound) && isset($pages[$explodedId[0]]))
                            {

                                $now = new Carbon();
                                $forbiddenPosts[] = [
                                    'fb_page_id'    => $pages[$explodedId[0]],
                                    'fb_id'         => $explodedId[1],
                                    'message'       => \GuzzleHttp\json_encode($post['message']),
                                    'permalink_url' => $post['permalink_url'],
                                    'created_time'  => (new Carbon($post['created_time']))->setTimezone(config('app.timezone')),
                                    'created_at'    => $now,
                                    'updated_at'    => $now,
                                    'banned_found'  => \GuzzleHttp\json_encode($stringsFound),
                                ];
                            }
                        }
                    }
                    else
                    {
                        \Log::warning("Detection - Batch Response", $responseBody);
                    }
                }
                $inserted = ForbiddenPost::insert($forbiddenPosts);
            } catch (FacebookResponseException $e)
            {
                \Log::warning("Detection - Batch Request", $e->getMessage());
            } catch (FacebookSDKException $e)
            {
                \Log::warning("Detection - Batch Request", $e->getMessage());
            }
        }
    }

}