<!doctype html>
<head>
    <title>Webform Processor</title>
</head>
<body>
<h1>Webform Processor Script</h1>
<p>A simple little form processor for passing info along from a web form submission.</p>
<p>More info on <a href="https://github.com/iCaspar/form-mailer-app">Github</a>.</p>
<p>Use the form below for testing:</p>

<form id="contact-form" method="post">
    <label for="name">Name: </label><input type="text" name="name" id="name" size="30"><br>
    <label for="from">Email: </label><input type="email" name="from" id="from" size="30"><br>
    <label for="message">Message: </label><br>
    <textarea name="message" id="message" placeholder="Type your message here." rows="3" cols="30"></textarea><br>
    <input name="submit" id="submit" type="submit" value="Send">
    <div id="contact-form-response-container"></div>
</form>
<script>
    var contactForm = document.getElementById("contact-form");
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const data = new FormData(contactForm);

        fetch('http://localhost/init.php', {
            method: "POST",
            cache: "no-cache",
            headers: {
                "X-ICaspar-Key": "superSecretKey"
            },
            body: data
        })
            .then(function (response) {
                return response.text();
            })
            .then(function (responseText) {
                if (responseText.includes('Succeeded')) {
                    responseText = 'Your message has been sent! Thank you!'
                }
                
                const responsePara = document.createElement('p');
                const responseTextNode = document.createTextNode(responseText);
                responsePara.appendChild(responseTextNode);

                document.getElementById('contact-form-response-container').appendChild(responsePara);
            })
            .catch(function (response) {
                console.log('Send failed.');
            });
    });
</script>
</body>
