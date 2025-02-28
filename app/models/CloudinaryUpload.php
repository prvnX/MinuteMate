<?php
class CloudinaryUpload{
    protected $cloud_name = "dbleqzcp4"; // Should replace with env variable
    protected $api_key = "481695248511879"; // Should replace with env variable
    protected $api_secret = "C5Ji1A1fv5y1gxPdDEgJicg1Xbw"; // Should replace with env variable
    protected $upload_preset = "my_present"; // Should replace with env variable
    
    private $uploadedMedia=[]; //store the uploaded media links and data here

    private function getUploadUrl($fileType)
    {
        if (strpos($fileType, 'image') !== false) {
            return "https://api.cloudinary.com/v1_1/{$this->cloud_name}/image/upload";
        } elseif (strpos($fileType, 'video') !== false) {
            return "https://api.cloudinary.com/v1_1/{$this->cloud_name}/video/upload";
        } else {
            return "https://api.cloudinary.com/v1_1/{$this->cloud_name}/raw/upload";
        }
    }

    private function makeCurlRequest($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch) . "<br>";
        }
        curl_close($ch);

        return json_decode($response, true);
    }

    public function uploadFiles($files){
        foreach ($files['tmp_name'] as $key => $tmpFilePath){
            if (!empty($tmpFilePath)) {
                $file = new CURLFile($tmpFilePath, $files['type'][$key], $files['name'][$key]);
                $fileType = $files['type'][$key];
                $upload_url = $this->getUploadUrl($fileType);
                $post_data = [
                    "file" => $file,
                    "api_key" => $this->api_key,
                    "timestamp" => time(),
                    "upload_preset" => $this->upload_preset
                ];
                $response = $this->makeCurlRequest($upload_url, $post_data);
                if (isset($response['secure_url'])) {
                    $url = $response['secure_url'];
                    $name=$files['name'][$key];
                    $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
                    $ext = isset($pathInfo['extension']) ? $pathInfo['extension'] : 'unknown';
                    $this->uploadedMedia[$key] = [
                        'name' => $name,
                        'url' => $url,
                        'ext' => $ext
                    ];
                }
            }
        }
            return $this->uploadedMedia;
        }
    }
