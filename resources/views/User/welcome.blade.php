<style>
    /* Style the container */
    .container {
        position: relative;
        align-items: center;
        align-content: center;
        justify-content: center;
        margin: auto;
        width: 50vw;
        border-radius: 0.5rem;
        box-shadow: var(--shadow);
        box-sizing: border-box;
        padding: 20px;
        margin-bottom: 2rem;
        background-color: var(--secondary-white-bg);
        color: var(--dark-color-text) !important;
    }

    /* Style the top section */
    .cls-1 {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cls-1 img {
        height: 40px;
    }

    .cls-1 h3 {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 1.5rem;
        color: var(--main-color);
    }

    /* Style the heading */
    h3 {
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: var(--main-color);
        text-align: left;
    }

    p {
        color: var(--dark-color-text) !important;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 600;
        line-height: 20px;
        word-break: 5px;
    }

    .general-rules>h3::after {
        content: "\1F50D";
        color: red;
        width: 30px;
        height: 30px;
        text-align: center;
        font-size: 1.5rem;
    }

    span {
        color: #0b3996;
    }

    /* Style the rules list */
    ol {
        margin-left: 1.5rem;
    }

    ol li {
        margin-bottom: 0.5rem;
        color: var(--dark-color-text) !important;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 500;
        line-height: 20px;
        word-break: 5px;
    }

    .how-to-wiki>h3::after {
        content: "\2764";
        color: red;
        width: 30px;
        height: 30px;
        text-align: center;
        font-size: 1.5rem;
    }

    /* Style the submit buttons */
    input[type="submit"] {
        background-color: var(--main-color);
        border: none;
        color: #ddd;
        font-size: 1rem;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    input[type="submit"]:hover {
        background-color: #08418a;
    }

    /* Style the payment section */
    .payment,
    .hosting {
        margin-top: 2rem;
        border-top: 1px solid #c1c1c1;
        padding-top: 2rem;
    }

    .payment p,
    .hosting p {
        margin-bottom: 1rem;
    }

    .payment li,
    .hosting li {
        margin-bottom: 0.5rem;
    }

    .payment span,
    .hosting span {
        color: var(--main-color);
    }

    .store>h3::after {
        content: "\1F3EC";
        text-align: center;
        font-size: 1.1rem;
        line-height: 1;
        width: 20px;
        height: 20px;
        color: #0b3996;
    }

    .highly-recommend-stuffs>h3::after {
        content: "\2714";
        text-align: center;
        font-size: 1.5rem;
        line-height: 1;
        width: 20px;
        height: 20px;
        color: #0b3996;
    }

    .payment>h3::after {
        content: "\1F4B0";
        text-align: center;
        font-size: 1.5rem;
        line-height: 1;
        width: 20px;
        height: 20px;
        color: #0b3996;
    }

    /* Style the recommended list */
    .highly-recommend-stuffs ol {
        /* list-style-type: none; */
        list-style: square;
        padding-left: 0;
    }

    .highly-recommend-stuffs li {
        margin-bottom: 0.5rem;
    }

    .highly-recommend-stuffs input[type="submit"] {
        background-color: transparent;
        color: var(--main-color);
        border: none;
        padding: 0;
        text-decoration: underline;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .highly-recommend-stuffs input[type="submit"]:hover {
        color: #08418a;
    }

    .complete label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        color: var(--dark-color-text) !important;
    }

    .complete input[type="checkbox"] {
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #555;
        outline: none;
        cursor: pointer;
    }

    .complete input[type="checkbox"]:checked::before {
        content: "\2714";
        display: block;
        text-align: center;
        font-size: 1.5rem;
        line-height: 1;
        color: var(--dark-color-text) !important;
        background-color: #555;
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }

    .complete input[type="checkbox"]:focus-visible {
        box-shadow: 0 0 0 2px #ddd;
    }
</style>

<form action="/market/welcome/{{ $user->public_name }}/read" method="post">
    @csrf
    <div class="container">
        <div class="cls-1">
            <img src="" alt="🤝" srcset="" style="font-size: 2em;">
            <h3>WELCOME, {{ $user->public_name }}</h3>
        </div>
        <hr>
        <div class="general-rules">
            <h3>General Site Rules</h3>
            <ol>
                <li>Always treat other users with respect and kindness.</li>
                <li>Provide fair and honest feedback; understand possible issues on the other end.</li>
                <li>Describe your goods properly; tell others the truth while maintaining your OPSEC standards.</li>
                <li>Report violations; provide guidance for those in need. We are stronger together.</li>
                <li>Everything related to child pornography (cp), racism, weapons, poisons, and scams is strictly forbidden.</li>
                <li>Insults, personal attacks, or any similar negative behavior is not tolerated.</li>
                <li>Performing unwanted actions on the website is strictly forbidden (e.g., editing forms, sending bad requests, ...).</li>        
                <li>For every 404 page you sow will be recorded, and this will cause your account to be banned by our auto ban system!</li>
                <li>Our market does not keep logs, which means we delete messages, orders (dispute closed, completed, canceled), notifications, ... after 10-15 days.</li>
            </ol>
        </div>
        <div>
            <h3>Vendors Account  ~= Stores</h3>
            <p>What is a store in the Whales Marketplace? A store is like being a vendor in any other market. Store bonds cost <span>{{ \App\Models\StoreRule::where('is_xmr', true)->first()->name }} XMR</span>, Store fees are non-refundable to uphold market integrity.</p>
            <p>To pay for a store bond, you need to:</p>
            <ol>
                <li>Have a PGP key in your market account and enable 2FA!!!</li>
                <li>Deposit money into your market wallet.</li>
                <li>Go to "Settings > Wallet > Deposit" and follow the instructions.</li>
                <li>Go to "Settings > Store Key" and click on it to read every detail of the Store Rules.</li>
                <li>Answer some questions.</li>
                <li>Proceed to accept and pay.</li>
                <li>If your wallet has the required sum of {{ \App\Models\StoreRule::where('is_xmr', true)->first()->name }} XMR, you will receive a notification with your store key.</li>
                <li>After that, copy the key into your computer.</li>
                <li>Then click on the "store + icon" then provide your store info and enter the store key at the bottom if the key is valid, your store will be pending approval.</li>
            </ol>
        </div>
        <div class="highly-recommend-stuffs">
            <h3>Highly Recommended Things</h3>
            <ol>
                <li>Use only "Tail OS" to access DW, search on google if you don't know about it.</li>
                <li>PGP keys (Enabling 2FA) are highly recommended.</li>
                <li>Disabling Javascript is highly recommended.</li>
                <li>If you are new to the DW read the "Drugs User Bible and DarkNet User Bible" you can find it on dread
                    forum!</li>

            <li>You want be more safe when using drugs? we recommend you going to dread and find the "Harm Reduction".</li>
                <li>Be part of our community on dread forum "any_dread_url/whalesMarket", coming soon.</li>
                <li>Be part of our community on pitch forum "any_pitch_url/@whalesMarket", coming soon.</li>
                {{-- <li>For optimal use of i2p, it is recommended to utilize either the Firefox or Tor browser exclusively.</li>
                <li>Employ a dedicated browser solely for i2p activities to enhance security and privacy.</li> --}}
                <li>To prevent phishing risks, keep this URL and check for your private mirro link if available.</li>
                <li>Always stay sefe.</li>
            </ol>
        </div>
        <div class="payment">
            <h3>Secure Payment Method</h3>
            <p>Ensuring the safety and privacy of our customers is our top priority at Whales Market. To maintain the highest standards, we have chosen to exclusively accept Monero cryptocurrency as our preferred payment method. Here are several key reasons behind this decision:</p>
            <ul>
                <li>Monero transactions are confidential by default, providing a higher level of privacy compared to transparent ledgers found in other cryptocurrencies.</li>
                <li>Ring signatures and stealth addresses employed by Monero enhance transaction anonymity, making it significantly more challenging to trace funds back to their source.</li>
                <li>Monero's fungibility ensures that each unit is indistinguishable from another, preventing any potential discrimination or blacklisting based on transaction history.</li>
                <li>The dynamic block size and frequent updates in Monero's protocol contribute to a resilient and adaptable network, staying ahead of potential vulnerabilities.</li>
                <li>With Monero, there is no public record linking your transactions to your identity, offering a shield against surveillance and safeguarding your financial history.</li>
            </ul>
            <p>These reasons collectively reinforce our commitment to providing a secure and private platform for your transactions, making Monero the ideal choice for our customers at Whales Market.</p>
        </div>
        
        {{-- <div class="hosting">
            <h3>Why We Host Our Site on I2P only?</h3>
            <p>Whales Market has chosen to host our site on the I2P network for several strategic reasons, setting us apart in terms of security, privacy, and user experience. Here's a comparison with other hosting options:</p>
            <ul>
                <li><strong>Anonymity:</strong> I2P provides a higher level of anonymity compared to conventional clearnet hosting or tor, protecting both our users and our platform from potential threats.</li>
                <li><strong>Resistance to Surveillance:</strong> Hosting on I2P shields our site from mass surveillance, ensuring that user data and activities remain private and beyond the reach of third-party monitoring.</li>
                <li><strong>Decentralization:</strong> I2P operates as a decentralized network, reducing the risk of single points of failure and enhancing the overall resilience of our platform against downtime or attacks.</li>
                <li><strong>Increased Privacy:</strong> Unlike hosting on traditional servers, I2P's architecture inherently prioritizes privacy, aligning with our commitment to provide a secure environment for our users.</li>
                <li><strong>Community Trust:</strong> By choosing I2P, we contribute to and participate in a community that values privacy and security, fostering trust among users who prioritize these principles.</li>
            </ul>
            <p>These reasons reflect our dedication to creating a secure and private online marketplace, and we believe that hosting on I2P aligns perfectly with our commitment to safeguarding the interests of our valued users at Whales Market.</p>
        </div> --}}
        
        <div class="complete">
            <label>
                <input type="checkbox" name="understood" required>
                <span>I have read everything and understood, and I agree to the rules.</span>
            </label>
            <div style="text-align: center;">
                <input type="submit" name="proceed-next" value="Proceed">
            </div>
        </div>
    </div>
</form>
