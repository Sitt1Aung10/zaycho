<style>
.paymentSuccess {
     display: none;
}
.priceHolder {
    width: 50px;
    height: 50px;
}
#kpay > h4 {
    color: #fff;
    display: inline-block;
    padding: 10px 10px;
    box-sizing: border-box;
}
#kpay {
    background-color: #0051A0;
}
#ayapay > h4 {
    color: #000;
    display: inline-block;
    padding: 10px 10px;
    box-sizing: border-box;
}
#ayapay {
    background-color: #DA2745;
}
#wavepay > h4 {
    color: #000;
    display: inline-block;
    padding: 10px 10px;
    box-sizing: border-box;
}
#wavepay {
    background-color: #FFE71A;
}
#uabpay > h4 {
    color: #fff;
    display: inline-block;
    padding: 10px 10px;
    box-sizing: border-box;
}
#uabpay {
    background-color: #000;
    border: 1px solid #fff;
}
.payBtn , .cancel{
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
        <div >
          <div class="paymentSuccess" style="color: green;"> 
               Payment Success
          </div>
        <div  class="payment_method"  id="kpay">
        <h4>KBZ PAY</h4>
        <br>
        <input type="text">
        <br>
        <span class="priceHolder"></span>
        <button type="submit" class="payBtn">Pay</button>
        <button type="submit" class="cancel">Cancel</button>
    </div>

    <div class="payment_method"  id="ayapay">
        <h4>AYA PAY</h4>
        <br>
        <input type="text">
        <br>
        <span class="priceHolder"></span>
        <button type="submit" class="payBtn">Pay</button>
        <button type="submit" class="cancel">Cancel</button>
    </div>

    <div class="payment_method"  id="wavepay">
        <h4>WAVE PAY</h4>
        <br>
        <input type="text">
        <br>
        <span class="priceHolder"></span>
        <button type="submit" class="payBtn">Pay</button>
        <button type="submit" class="cancel">Cancel</button>
    </div>

    <div class="payment_method"  id="uabpay">
        <h4>UAB PAY</h4>
        <br>
        <input type="text" >
        <br>
        <span class="priceHolder"></span>
        <button type="submit" class="payBtn">Pay</button>
        <button type="submit" class="cancel">Cancel</button>
    </div>

</div>
    </div>
    <script>
        const kpayBtn =document.querySelector(".kpayBtn")
        const ayaBtn =document.querySelector(".ayaBtn")
        const waveBtn =document.querySelector(".waveBtn")
        const uabBtn =document.querySelector(".uabBtn")
        const cancel =document.querySelector('#cancel')
        const paymentSuccess =document.querySelector(".paymentSuccess")

        const kpay =document.getElementById("kpay")
        const ayapay =document.getElementById("ayapay")
        const wavepay =document.getElementById("wavepay")
        const uabpay =document.getElementById("uabpay")

        kpayBtn.addEventListener("click",function() {
            kpay.classList.add("activePayment")
        })
        ayaBtn.addEventListener("click",function() {
            ayapay.classList.add("activePayment")
        })
        waveBtn.addEventListener("click",function() {
            wavepay.classList.add("activePayment")
        })
        uabBtn.addEventListener("click",function() {
            uabpay.classList.add("activePayment")
        })
        const cancelButtons = document.querySelectorAll('.cancel');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            button.parentElement.classList.remove("activePayment")
        });
    });

    const payBtn =document.querySelectorAll(".payBtn")
    payBtn.forEach(button => {
        button.addEventListener('click', function() {
            // Show the success message
            paymentSuccess.style.display = 'block';
            button.parentElement.classList.remove("activePayment")

            // Hide the success message after 3 seconds (3000 milliseconds)
            setTimeout(() => {
                paymentSuccess.style.display = 'none';
            }, 8000);
        });
    });
    </script>