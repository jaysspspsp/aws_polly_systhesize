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
                    <select id="languages" name="languages" style="width: 100%" onchange="set_voice();">
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

                <td colspan="2">

                </td>

            </tr>
            <tr>
                <td colspan="4">
                    <button type="button" onclick="get()" style="width: 100%; height: 40px">
                        Listen
                    </button>
                </td>
            </tr>
            <tr>
                <td clospan="4">
                    <audio id="audio" controls>
                    </audio>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <a id="download_link">download</a>
                </td>
            </tr>
        </table>
    </form>


    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.132.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
         $(document).ready(set_languages);
        function get() {
            var text = $('#inputText').val()
            $('#audio').attr("src", '');

            var params = new URLSearchParams();
            params.append('text', text)
            axios.post("<?= base_url('get_audio') ?>", params)
                .then(function(response) {
                    // console.log(response.data);
                    $('#audio').attr("src", "data:audio/mpeg;base64," + response.data);
                    $('#download_link').attr('href', "data:audio/mpeg;base64," + response.data);
                    $('#download_link').attr('download', "output.mp3");
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
        function set_voices(){
            axios.get("<?= base_url('describe_voices') ?>")
                .then(function(response) {
                    console.log(response.data);
                    for (i = 0; i < response.data.length; i++) {

                        let e = `<option value='${ response.data[i]['Id'] }'>${ response.data[i]['Id'] }</option>`;
                        $('#voice').append(e);

                    }

                })
                .catch(function(error) {
                    console.log(error);
                });
        }
        set_voices();
       

        function set_languages() {

            axios.get("<?= base_url('get_languages') ?>")
                .then(function(response) {
                    console.log(response.data[0]);
                    for (i = 0; i < response.data.length; i++) {

                        let e = `<option value='${ response.data[i] }'>${ response.data[i] }</option>`;
                        $('#languages').append(e);

                    }

                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    </script>

</body>

</html>