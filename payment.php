<style>
    .paymentSuccess {
        display: none;
    }

    .priceHolder {
        width: 50px;
        height: 50px;
    }

    .payment_method {
        padding-left: 50px;
        box-sizing: border-box;
    }

    #kpay>h4 {
        color: #fff;
        display: inline-block;
        margin: 0;
    }

    #kpay {
        background-color: #0051A0;
    }

    #ayapay>h4 {
        color: #000;
        display: inline-block;
        margin: 0;
    }

    #ayapay {
        background-color: #DA2745;
    }

    #wavepay>h4 {
        color: #000;
        display: inline-block;
        margin: 0;
    }

    #wavepay {
        background-color: #FFE71A;
    }

    #uabpay>h4 {
        color: #fff;
        display: inline-block;
        margin: 0;
    }

    #uabpay {
        background-color: #000;
        border: 1px solid #fff;
    }

    .voucher {
        color: #fff;
        border: 1px solid #fff;
        font-size: 14px;
        padding: 5px;
        box-sizing: border-box;
    }

    .payBtn,
    .cancel {
        color: #fff;
        background-color: transparent;
    }
</style>
<div id="payment">
    <img class="kpayBtn" src="img/kpay.webp" alt="kpay">
    <img class="ayaBtn" src="img/aya.png" alt="aya pay">
    <img class="waveBtn" src="img/wave.jpg" alt="wave pay">
    <img class="uabBtn" src="img/uab.jpg" alt="uab pay">
    <img class="cod" src="img/cod.jpg" alt="cod pay">
    <div>
        <div class="paymentSuccess" style="color: green;">
            Payment Success
        </div>
        <div class="payment_method" id="kpay">
            <h4>KBZ PAY</h4>
            <br>
            <img src="img/kpayqr.png" width="80px">
            <br>
            <span style="font-size:14px;" class="priceHolder"></span>
            <br>
            <label class="voucher" for="voucher">Upload Voucher</label>
            <input type="file" id="voucher" name="voucher" style="display: none;">
            <br>
            <button type="submit" class="payBtn">Pay</button>
            <button type="submit" class="cancel">Cancel</button>
        </div>

        <div class="payment_method" id="ayapay">
            <h4>AYA PAY</h4>
            <br>
            <img src="img/kpayqr.png" width="80px">
            <br>
            <span style="font-size:14px;" class="priceHolder"></span>
            <br>
            <label class="voucher" for="voucher">Upload Voucher</label>
            <input type="file" id="voucher" name="voucher" style="display: none;">
            <br>
            <button type="submit" class="payBtn">Pay</button>
            <button type="submit" class="cancel">Cancel</button>
        </div>

        <div class="payment_method" id="wavepay">
            <h4>WAVE PAY</h4>
            <br>
            <img src="img/kpayqr.png" width="80px">
            <br>
            <span style="font-size:14px;" class="priceHolder"></span>
            <br>
            <label class="voucher" for="voucher">Upload Voucher</label>
            <input type="file" id="voucher" name="voucher" style="display: none;">
            <br>
            <button type="submit" class="payBtn">Pay</button>
            <button type="submit" class="cancel">Cancel</button>
        </div>

        <div class="payment_method" id="uabpay">
            <h4>UAB PAY</h4>
            <br>
            <img src="img/kpayqr.png" width="80px">
            <br>
            <span style="font-size:14px;" class="priceHolder"></span>
            <br>
            <label class="voucher" for="voucher">Upload Voucher</label>
            <input type="file" id="voucher" name="voucher" style="display: none;">
            <br>
            <button type="submit" class="payBtn">Pay</button>
            <button type="submit" class="cancel">Cancel</button>
        </div>

    </div>
</div>
<script>
    const kpayBtn = document.querySelector(".kpayBtn")
    const ayaBtn = document.querySelector(".ayaBtn")
    const waveBtn = document.querySelector(".waveBtn")
    const uabBtn = document.querySelector(".uabBtn")
    const cancel = document.querySelector('#cancel')
    const paymentSuccess = document.querySelector(".paymentSuccess")

    const kpay = document.getElementById("kpay")
    const ayapay = document.getElementById("ayapay")
    const wavepay = document.getElementById("wavepay")
    const uabpay = document.getElementById("uabpay")

    kpayBtn.addEventListener("click", function() {
        kpay.classList.add("activePayment")
    })
    ayaBtn.addEventListener("click", function() {
        ayapay.classList.add("activePayment")
    })
    waveBtn.addEventListener("click", function() {
        wavepay.classList.add("activePayment")
    })
    uabBtn.addEventListener("click", function() {
        uabpay.classList.add("activePayment")
    })
    const cancelButtons = document.querySelectorAll('.cancel');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            button.parentElement.classList.remove("activePayment")
        });
    });

    const payBtn = document.querySelectorAll(".payBtn")
    payBtn.forEach(button => {
        button.addEventListener('click', function() {
            const voucherInput = button.parentElement.querySelector("input[type='file']");
            const formData = new FormData();
            if (voucherInput.files.length === 0) {
                alert("Please upload a voucher before proceeding.");
                return;
            }
            formData.append('voucher', voucherInput.files[0]); // Send the file via AJAX to the server 
            fetch
            // 
            ('upload_voucher.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                if (data.includes("Payment Success")) {
                    // Show the success message 
                    paymentSuccess.style.display = 'block';
                    button.parentElement.classList.remove("activePayment");
                } else {
                    alert(data);
                }
            }).catch(error => console.error('Error:', error));
        });
    });
</script>