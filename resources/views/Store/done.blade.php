<style>
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
        margin: 10px;
        border: 7px solid #0b3996;
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

    .container-ring {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
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
<div class="container-ring">
    <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="status">
        <h1>You have successfully created a listing. It is under review.</h1>
        <p style="text-align: center; color: green;">Admin or Moderators will review your product as soon as possible.</p>
        <h2>*^____^*</h2>
        <div style="text-align: center;">
        <a href="/store/add-listings" style="background-color: #0b3996; padding: 5px; text-decoration: none; color: white; border-radius: 5px;">+ add listing</a><br><br>
    </div>
    </div>
</div>