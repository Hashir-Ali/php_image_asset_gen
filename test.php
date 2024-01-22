<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
    <!--<canvas id="myCanvas" width="4096" height="4096" style="display: none;"></canvas>
    <img src="" alt="" id="sn" width="4096" height="4096">-->

    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4 mb-4">
                <div style="overflow: hidden;margin-bottom: 0.5rem;">
                    <h3 style="float: left;margin: 0;">Combinations <span id="span_t" class="text-success" style="font-size: 14px;display: none;">(All Avatars Generated)</span></h3>
                    <button type="button" class="btn btn-success" style="float: right;" id="downloadAllBtn" disabled>Download All</button>
                </div>
                <ul class="list-group" id="combinations">
                <?php
                if ($handle = opendir('combinations')) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && strtolower(mb_substr($entry, 0, 1)) != ".") {
                            ?>
                            <li class="list-group-item list-group-item-action" data-filename="<?php echo $entry;?>">
                                <span style="font-weight: bold;font-size: 18px;">&#10065; <?php echo $entry;?></span>

                                <span class="badge rounded-pill" style="float: right;display: none;">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </span>
                            
                                <ul class="list-group mt-3 mb-2" id="sub-list" style="display: none;">
                                    <li class="list-group-item sub-content">
                                        <a href="#" id="genFH1" class="genFH1"><img alt="..." id="genF" class="img-thumbnail" style="width: 100px;float: left;margin-right: 14px;"></a>
                                        <h4 class="genT"></h4>

                                        <ul class="list-group list-group-flush" id="traits-list">
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                    }
                
                    closedir($handle);
                }
                ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="mergeImages.js"></script>

    <script>
        //import mergeImages from 'merge-images';
 
        /*mergeImages(['traits/bac006_cloud.png', 'traits/sup004_skeleton.png', 'traits/clo025_canary_polo.png'])
        .then(b64 => 
        setTimeout(function(){
            downloadBase64File(b64, 'test.png')
        }, 10)
        );*/

        

    $(document).ready(function(){
        function dataURItoBlob(dataURI) {
            var byteString = atob(dataURI.split(',')[1]);
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], { type: 'image/png' });
        }

        let generated_image_t;

        function downloadBase64File(contentBase64) {

            var a = $("<a>")
                .attr("href", contentBase64)
                .attr("download", generated_image_t)
                .appendTo("body");
            console.log(a);
            a[0].click();

            //a.remove();

            setTimeout(function(){
                index++;
                if(index < $(combinations).children('li').length){
                    setTimeout(function(){
                        window.close();
                    }, 3000);
                    window.location.href = 'test.php?n=' + index;
                    // window.open('test.php?n=' + index);
                    callGenerator();
                }
            }, 10);

            


            /*$.ajax({
                url: 'combinations/test',
                type: 'GET',
                error: function(data){
                   
                },
                success: function(data){
                    
                }
            });*/

            //console.log(contentBase64);

            //var formData = new FormData();
            //formData.append('sn', true);

            /*var img = new Image();
            img.src = contentBase64;

            var myformData = new FormData();        
            myformData.append('leadid', true);
            //myformData.append('img', $(img).get(0).files[0]);
            myformData.append('img', dataURItoBlob(contentBase64));

            $.ajax({
                method: 'post',
                processData: false,
                contentType: false,
                cache: false,
                data: myformData,
                enctype: 'multipart/form-data',
                url: 'includes/gen_3.php',
                error: function(data){
                   
                },
                success: function(data){
                    
                }
            });*/
        }



        $('[data-dismiss="modal"]').click(function(){
            $('.modal').modal('hide');
        });
        var combinations = $('#combinations');
        var index = <?php if(isset($_GET['n'])){echo $_GET['n'];}else{echo '0';} ?>;
        function callGenerator(){
            var combinationsLi = $(combinations).children('li').eq(index);
            var filename = $(combinationsLi).attr('data-filename');
            $(combinationsLi).children('.badge').show();

            
            $.ajax({
                url: 'includes/gen_2.php',
                type: 'POST',
                data: {'combinations' : true, 'filename' : filename, 'n' : index},
                error: function(data){
                    $(combinationsLi).children('.badge').hide();
                    $(combinationsLi).children('#sub-list').show();
                    $(combinationsLi).children('#sub-list').children('.sub-content').html(data);

                    index++;
                    if(index < $(combinations).children('li').length){
                        callGenerator();
                    }

                    if(index >= $(combinations).children('li').length){
                        $('#span_t').show();
                        $('#downloadAllBtn').prop('disabled', false);
                    }
                },
                success: function(data){
                    $(combinationsLi).children('.badge').hide();
                    if(data != 'Combinations file not valid.'){
                        var result = JSON.parse(data);
                        console.log(result);
                        //$(combinationsLi).children('#sub-list').children('.sub-content').find('#genF').attr('src', result.generated_image);
                        
                        $(combinationsLi).children('#sub-list').children('.sub-content').find('.genT').html(result.generated_image_t + ' <span style="font-size: 14px;">(<a href="'+result.generated_image+'" download="'+result.generated_image_t+'" class="d">Download</a>)<span>');

                        var all_traits = [];

                        generated_image_t = result.generated_image_t;
                        
                        var k = Object.keys(result.traits);
                        var v = Object.values(result.traits);
                        var link = '';
                        for (let index = 0; index < k.length; index++) {
                            // if(String(k[index]).includes('_val') == false){
                                if(true){
                                var s = '';
                                var g = '';
                                if(result.traits[k[index]] == "Didn't match any trait." || result.traits[k[index]] == "Failed to open."){
                                    s = 'color: red;';
                                    g = ' (' + result.traits[k[index] + '_val'] + ')';
                                }

                                
                                // console.log(k[index]);
                                
                                if(String(k[index]).includes('_val') ){
                                    // link += result.traits[k[index]]+'/';
                                    link = link + result.traits[k[index]];
                                    $(combinationsLi).children('#sub-list').children('.sub-content').find('#traits-list').append('<li class="list-group-item d-flex align-items-center"><img src="' + link + '" alt="" style="width: 30px;padding: 2px;margin-right: 6px;" class="img-thumbnail"> <strong style="margin-right: 3px;">' + k[index] + '</strong> - <span style="'+s+'margin-left: 3px;">' + result.traits[k[index]] + g + '</span></li>');

                                    all_traits.push(link);
                                    link = '';
                                }else{
                                    
                                    link += './traits/'+result.traits[k[index]]+'/';
                                }
                                // console.log(all_traits);
                            }
                        }
                        console.log(all_traits);
                        mergeImages(all_traits, {format: 'image/jpeg',
                            quality: 0.5,
                            width:4000,
                            height: 4000,
                        })
                        .then(b64 => 
                        setTimeout(function(){
                            downloadBase64File(b64)
                        }, 0)
                        );



                    }else{
                        $(combinationsLi).children('#sub-list').children('.sub-content').html(data);
                    }

                    $(combinationsLi).children('#sub-list').show();


                    if(index >= $(combinations).children('li').length){
                        $('#span_t').show();
                        $('#downloadAllBtn').prop('disabled', false);
                    }
                }
            });
        }

        callGenerator();

        $('.genFH1').click(function(){
            $('#modalPreviewImg').attr('src', $(this).find('#genF').attr('src'));

            $('.modalD').attr('href', $(this).parent().find('.genT').find('a').attr('href'));
            $('.modalD').attr('download', $(this).parent().find('.genT').find('a').attr('download'));

            $('#previewModal').modal('show');
        });

        $('#downloadAllBtn').click(function(){
            for (let index = 0; index < $(combinations).children('li').length; index++) {
                console.log($(combinations).children('li').eq(index).children('#sub-list').children('.sub-content').find('a.d'));
                $(combinations).children('li').eq(index).children('#sub-list').children('.sub-content').find('a.d').get(0).click();
                
            }
        });
           
    });


            //$('html,body').animate({
            //scrollTop: $(combinationsLi).offset().top},
            //'slow');

            /*$.ajax({
                url: 'combinations/' + filename,
                type: 'GET',
                error: function(data){
                    $(combinationsLi).children('.badge').hide();
                    $(combinationsLi).children('#sub-list').show();
                    $(combinationsLi).children('#sub-list').children('.sub-content').html(data);

                    index++;
                    if(index < $(combinations).children('li').length){
                        callGenerator();
                    }

                    if(index >= $(combinations).children('li').length){
                        $('#span_t').show();
                        $('#downloadAllBtn').prop('disabled', false);
                    }
                },
                success: function(data){
                    $(combinationsLi).children('.badge').hide();
                    if(data != ''){
                        var result = JSON.parse(data);
                        var filename = result.name;
                        console.log(result);
                        console.log(filename);
                    }else{
                        $(combinationsLi).children('#sub-list').children('.sub-content').html('Combinations file not valid.');
                    }
                }
            });*/
    </script>
</body>
</html>