<?php
if(isset($_POST['combinations'])){
    function clean($string) {
        $string = str_replace(' ', '-', $string);
     
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /*function checkFileExists($image_1, $path_file, $n = 0){
        if(file_exists($path_file . '.png') == true && $n == 0){
            $n++;
            checkFileExists($image_1, $path_file, $n);
        }else if(file_exists($path_file . '-' . $n . '.png') == true && $n > 0){
            $n++;
            checkFileExists($image_1, $path_file, $n);
        }else{
            if($n == 0){
                $p = $path_file;
            }else{
                $p = $path_file . '-' . $n;
            }
            imagepng($image_1, $p . '.png');
        }
    }*/

    $filename = $_POST['filename'];
    $filePath = '../combinations/' . $filename;
    $jsonFilePath = '../final.json';

    $combinationsFile = fopen($filePath, "r");

    if($combinationsFile){
        $contents = fread($combinationsFile, filesize($filePath));
        $ar = json_decode($contents, true);

        if($ar['attributes']){
            $attributes = $ar['attributes'];
            $traits_filename = array();
            $result = array("success" => true);
            $result_traits = array();
            foreach ($attributes as $key => $value) {
                $trait_type = strtolower(mb_substr($value['trait_type'], 0, 3));
                $trait_value = strtolower(str_replace(' ', '_', str_replace('-', '_', $value['value'])));

                if ($handle = opendir('../traits')) {
                    $f = false;
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            //$entry is the folder name;;
                            //$entry2 is the file/image name...
                            
                            $first_match = strtolower(mb_substr($entry, 4, 3));

                            // last match shall be the name of the image file inside the trait type folder (value of $entry)..
                            $last_match = strtolower($entry);
                            // echo($entry);
                            // $ahaha = array('first_match' => $first_match, 'last_match' => $last_match, 'trait_value'=>mb_substr($trait_value, 0, 3), 'trait_type' => $trait_type);
                            // echo(json_encode($ahaha));
                            // echo('<br />');
                            // // echo($first_match == $trait_type);
                            // echo (strpos($last_match, $trait_value) !== false);
                            // echo $value['value'];

                            // && strpos($last_match, mb_substr($trait_value, 0, 3)) !== false
                            if ($first_match == $trait_type) {
                                if($handle2 = opendir('../traits/'.$entry)){
                                    //now check if the name of trait value matches with one of handle2...
                                    while(false !== ($entry2 = readdir($handle2))){
                                        if($entry2 != "." && $entry2 != ".."){
                                            
                                            // echo('trait value: '.$trait_value.'<br />');
                                            // echo('Entry2: '.$entry2.'<br />');

                                            if(strpos($entry2, explode('_',$trait_value)[0])){


                                                if($trait_type === 'ski' && $trait_value === 'human'){
                                                    // get the skin value from the new json file, for the current combination
                                                        // read the file content....
                                                        // convert json into php array and pick specific entry...
                                                        // pick specific entry
                                                    // get the value and assign that to $entry2 (filename) variable...
                                                    
                                                    if(file_exists($jsonFilePath)){
                                                        // Read the JSON file content
                                                        $jsonData = file_get_contents($jsonFilePath);
                                                        
                                                        // converting the JSON data into a PHP array
                                                        $data = json_decode($jsonData, true); // Set the second argument to true for an associative array

                                                        if($data !== null){
                                                            // var_dump($data);
                                                            // echo('Hahaha: '.$data[$filename]."<br />");
                                                            $entry2 = $data[$filename];
                                                        }
                                                    }
                                                }
                                                $f = true;
                                                $result_traits[$value['trait_type']] = $entry;
                                                $result_traits[$value['trait_type'] . '_val'] = $entry2;
                                                array_push($traits_filename, $entry2);
                                                break; // get out of trait specific folder, and search for other traits, if values matched
                                            }
                                        }
                                    }
                                }
                               
                               
                            }
                        }
                    }
                    // if($f == false){
                    //     $result_traits[$value['trait_type']] = "Didn't match any trait.";
                    //     $result_traits[$value['trait_type'] . '_val'] = $value['value'];
                    // }
                }else{
                    $result_traits[$value['trait_type']] = "Failed to open.";
                    $result_traits[$value['trait_type'] . '_val'] = $value['value'];
                }
            }
            $result['traits'] = $result_traits;
            // echo($traits_filename[0]);
            //if($_POST['n'] > 590){
                // $image_1 = imagecreatefrompng('../traits/' . $traits_filename[0]);

            /*$image_1 = imagecreatefrompng('../traits/' . $traits_filename[0]);
            $w = imagesx($image_1);
            $h = imagesy($image_1);
            imagealphablending($image_1, true);
            imagesavealpha($image_1, true);

            for ($i = 1; $i < sizeof($traits_filename); $i++) {
                $image_2 = imagecreatefrompng('../traits/' . $traits_filename[$i]);
                imagecopy($image_1, $image_2, 0, 0, 0, 0, $w, $h);
            }
            
            //ob_start();
            imagepng($image_1, '../avatars/' . clean($ar['name']) . '-' . generateRandomString(6) . '.png', 9);*/
            //}
            //$fn = checkFileExists($image_1, '../avatars/' . clean($ar['name']));
            //imagepng($image_1, $fn);
            //$buffer = ob_get_clean();
            //ob_end_clean();
            //$g_base64 = 'data:image/png;base64,' . base64_encode($buffer);

            //$result['generated_image'] = $g_base64;
            $result['generated_image_t'] = clean($ar['name']) . '.png';

            echo json_encode($result);
        }else{
            echo 'Combinations file not valid.';
        }
    }else{
        echo 'Combinations file not found.';
    }

    fclose($combinationsFile);
}else{
    echo 'Not allowed';
}
?>