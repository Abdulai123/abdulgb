<legend>Wallet Info</legend>
<form action="" method="post" class="form-container">
    <p><span>Unlock Balance</span> <br> <span>$0.00 / 0.000 XMR</span></p>
    <p><span>Locked Balance ( Escrow )</span> <br> <span>$0.00 / 0.000 XMR</span></p>
    <p><span>Total Balance </span> <br><span>$0.00 / 0.000 XMR</span></p>
</form>

<legend>Widthdrawing Money</legend>
<form action="" method="post" class="form-container"> 
<?php if (isset($_POST['next2'])) {

echo "You are trying to withdraw $" . $_POST['widthdraw-amount'] . "( 0.3333 XMR ), To this xmr adress <br>" . $_POST['widthdraw-xmr-adress'];

echo <<<END__
        <div>
            <p>VERIFY PGP</p>
            <textarea class="form-textarea">-----BEGIN PGP MESSAGE-----

            wcFMAwnM3axjYGJQAQ//S5i+JjtDY8v4CDGbTj5sQvX72KeDXd3Q3btcBv5M
            HMtssla62HVAos0FUkXmxn54KK7N8lYM4oXO1gamCm7BfNoQCFID25Gs0Ukv
            kozuxWxFbSFApinZaB1PgjT7cfgYXDyhesvHdgbfGHOS3NcqEnixUQ7a1pbk
            6sQLIU0Gd/OgoZipp+OMBMJwqlQeopiitqSsSDRu5QpqzVuqhSs4aPjEnMHV
            3nQr2cGBpJr0Vurh0BR/xs32IlrIGVfH5hHpzDuSdTmbEjcPvRMCHIYT0Nl2
            zS7mDZrDEOYkezj27by/rsy2+CfHK28932Z8cFSgdZp7kHhGycyX3/arEoUt
            0NTQcXFiF/TU+8nHtNNxK+UEF2Ph/vVF5gWEqyOPaNpn5l9qujkLC/NDZpiT
            jNw3RcpI9rTG4zFYdnFkcsFgaLo6qshMjrTbtN109JWBRmOWQvNcgc3mTNjl
            nzx58kiMm3VjL6x+SENY+8fMA73OcEv/yjn/kcXmYEn55DhPromBtqa5i3Ax
            a+wX36wB4/6zmKFYoVXpwKIm1YYOnH9tSpHUs07Kh8FiML9i1hnU0qhlfMQQ
            BIe8btwONtrNIhW5p2+eEhMo/qU8jc0iiXDbcVo0s7JDMd7y2qYknatYNWNO
            jNXDxaN0AHfPLR0IH+o435/LggRsZBSPEYxncoIshkHSVAH8tVRu1b0GYTQ1
            cjCmmJLfB3pA/6eCxfgkT10r9qni4l2Z/rHmfZVxwRvQde4LxlRjYlO3lxoN
            ZHtCI64apdwZDdN1IwoA0w4YPo/q31bxfne5/Q==
            =9w2r
            -----END PGP MESSAGE-----
            </textarea>
            <input type="text" class="form-input" id="pgp-code" name="pgp-code" placeholder="decrypted token" required>
        </div>
        <div>
            <p>SECURITY CODE</p>
            <input type="number" class="form-input" id="security-code" name="security-code" placeholder="Enter security code" required>
        </div>
END__;

//echo '<input type="submit" name="done_listing" value="Submit">';
echo ' <input type="submit" class="submit-nxt" name="" value="Widthdraw">';
} else { ?>
<input type="text" id="widthdraw-xmr-adress" class="form-input" name="widthdraw-xmr-adress" placeholder="XMR adress" required>
<input type="number" id="widthdraw-amount" class="form-input" name="widthdraw-amount" placeholder="Amount $0.00" required>

<input type="submit" class="submit-nxt" name="next2" value="Next">
<?php } ?>
</form>

<legend> Widthdraw History</legend>
<form action="">
<table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Amount</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>
  No widthdraw history found.
</form>
