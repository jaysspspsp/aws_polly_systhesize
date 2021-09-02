<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>aws polly Demo</title>
</head>

<body>
    <h1>AWS Polly</h1>
    <form action="<?= base_url('get_voice') ?>" method="post">
        <table cellspacing="16" cellpadding="16">
            <tr>
                <td>Select Languages</td>
                <td colspan="2">
                    <select id="languages" name="languages" onchange="set_voices()" style="width: 100%" onchange="set_voice();">
                        <option value="null">Select language</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Select Voice</td>
                <td colspan="2">
                    <select id="voice" name="voice" style="width: 100%">
                        <option value="null">Select Voice</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Enter Text</td>
                <td colspan="2">
                    <input type="text" name="text" id="inputText" placeholder="Enter your text here" style="width: 400px; height: 200px" aria-multiline="true" />
                </td>
            </tr>
            <tr>
                <!-- <td>Engine</td>
                <td>
                    nural<input type="radio" name="engine" id="nural">
                </td>
                <td>
                    natural<input type="radio" name="engine" id="natural">
                </td> -->

            </tr>
            <tr>
                <td clospan="2">
                    <audio id="audio" controls>
                    </audio>
                </td>
            
                <td colspan="2">
                    <a id="download_link">download</a>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <button type="button" onclick="get()" style="width: 100%; height: 40px">
                    Get TTS Representation
                    </button>
                </td>
            </tr>
            
        </table>
    </form>
    <div id="error">

    </div>


    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.132.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(set_languages);






        function get() {
            var lang = document.getElementById('languages').value;
            var voice = document.getElementById('voice').value;
            var text = $('#inputText').val()
            $('#audio').attr("src", '');
            let req_body = {
                text: text,
                language: lang,
                voice: voice
            };


            $.ajax({
                url: "<?= base_url("get_audio"); ?>",
                type: "POST",
                data: req_body,
                success: function(result) {
                    
                    const data = result;
                    
                    $('#audio').attr("src", "data:audio/mpeg;base64," + data);
                    $('#download_link').attr('href', "data:audio/mpeg;base64," + data);
                    $('#download_link').attr('download', "output.mp3");
                    
                },
                error: function(error) {
                   console.log(error);
                },
            });




      
        }









        function set_voices() {
            $('#voice').empty();
            var lang = document.getElementById('languages').value;
            $.ajax({
                url: "<?= base_url("describe_voices"); ?>",
                type: "POST",
                data: "language="+lang,
                success: function(result) {
                    
                    const voice = JSON.parse(result);
                    for (i = 0; i < voice.length; i++) {
                        
                        let e = `<option value='${ voice[i]['Id'] }'>${ voice[i]['Id'] } Gender : ${ voice[i]['Gender'] }</option>`;
                        $('#voice').append(e);

                    }
                    
                },
                error: function(error) {
                   console.log(error);
                },
            });
        }





        function set_languages() {

            // ajax  call

            $.ajax({
                url: "<?= base_url("get_languages"); ?>",
                type: "GET",
                data: '',
                success: function(result) {
                    const language = JSON.parse(result);



                    for (i = 0; i < language.length; i++) {

                        let e = `<option value='${ language[i] }'>${ language[i] }</option>`;
                        $('#languages').append(e);

                    }


                },
                error: function(error) {
                    $("#error").after(`<div class='alert alert-danger'>Error Occured.</div>`);
                    console.log(error)
                },
            });

        }
    </script>

</body>

</html>