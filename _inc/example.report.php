<html>
  <head>
    <script>
      function appendResults(text) {
        var results = document.getElementById('results');
        results.appendChild(document.createElement('P'));
        results.appendChild(document.createTextNode(text));
      }

      function makeRequest() {
        var request = gapi.client.urlshortener.url.get({
          'shortUrl': 'http://goo.gl/fbsS'
        });
        request.then(function(response) {
          appendResults(response.result.longUrl);
        }, function(reason) {
          console.log('Error: ' + reason.result.error.message);
        });
      }

      function init() {
        gapi.client.setApiKey('YOUR API KEY');
        gapi.client.load('urlshortener', 'v1').then(makeRequest);
      }
    </script>
    <script src="https://apis.google.com/js/client.js?onload=init"></script>
  </head>
  <body>
    <div id="results"></div>
  </body>
</html>