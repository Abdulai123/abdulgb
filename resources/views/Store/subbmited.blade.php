

<style>
    .w {
        text-align: center;
        color: #0080ff;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-weight: bolder;
        letter-spacing: 5;
    }

    .m {
        text-align: center;
        color: #0b3996;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-weight: bolder;
        letter-spacing: 3;
    }

    .lds-ring {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 8px;
        border: 10px solid #0b3996;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #0b3996 transparent transparent transparent;
    }

    .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }

    .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }

    .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }

    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .status {
        text-align: center;
        margin-top: 20px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .status h1 {
        font-size: 24px;
        font-weight: bold;
    }

    .status h2 {
        font-size: 18px;
    }
</style>
<title>Under Review</title>
<div class="container">
    <div class="img-name">
        <h1><span class="w">WHALES</span> <span class="m">STORE</span></h1>
    </div>
    <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="status">
        <h1>You have successfully created a store. It is under review.</h1>
        <h2>Admin will message you within the next 24 hours. Congrats!</h2>
        <h2>Just relax, everything will be ok.</h2>
        <h2>^_~</h2>
        <a href="/">Go Back</a>
    </div>
</div>