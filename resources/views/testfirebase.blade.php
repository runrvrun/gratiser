<html>
<title>Firebase Messaging Demo</title>
<style>
    div {
        margin-bottom: 15px;
    }
</style>
<body>
    <div id="token"></div>
    <div id="msg"></div>
    <div id="notis"></div>
    <div id="err"></div>
    <script src="https://www.gstatic.com/firebasejs/4.6.2/firebase.js"></script>
    <script>
        MsgElem = document.getElementById("msg")
        TokenElem = document.getElementById("token")
        NotisElem = document.getElementById("notis")
        ErrElem = document.getElementById("err")
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
        // var config = {
        //     apiKey: "AIzaSyDEe1uhBcCM_QfyYQIai5bqZKUVOoolee0",
        //     authDomain: "gratiser-9c141.firebaseapp.com",
        //     databaseURL: "https://gratiser-9c141.firebaseio.com",
        //     storageBucket: "",
        //     messagingSenderId: "427390741307",
        // };
        var config = {
            apiKey: "AIzaSyCtNZ5g6qMmkY_mSIZ0eHg4PBKNtA_5UbI",
            authDomain: "gratiser-cdd6d.firebaseapp.com",
            databaseURL: "https://gratiser-cdd6d.firebaseio.com",
            storageBucket: "",
            messagingSenderId: "884454267145",
        };
        firebase.initializeApp(config);

        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                MsgElem.innerHTML = "Notification permission granted. Next: hit notif-store API with above token to store data." 
                console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                TokenElem.innerHTML = "token is : " + token
            })
            .catch(function (err) {
                ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission to notify.", err);
            });

        messaging.onMessage(function(payload) {
            console.log("Message received. ", payload);
            NotisElem.innerHTML = NotisElem.innerHTML +'<hr/>'+ JSON.stringify(payload) 
        });
    </script>

    <body>

</html>