<!DOCTYPE html>
<html>
<head>
<style>
    body {
        background-color: #edf2f7;
        width: 100%;
        height: 100%;
        font-family: "Gill Sans", sans-serif;
    }
    .container {
        display: flex;
        justify-content: center;
        text-align: center;
        background-color: #edf2f7;
    }

    .sub-container {
        width: 50%;
        background-color: #fff;
        padding: 30px;
    }

    table {
  width: 100%;
  border-collapse: collapse;
}

    table, td, th {
  border: 1px solid;
}
.col {
    width: 33%;
    line-height:150%;
}

.col {
    width: 25%;
    line-height:150%;
}

</style>
</head>
<body>
<div class="container">
    <div class="sub-container">
        <div >
            <h1>YOU HAVE PLACED AN ORDER</h1>
            <h4 style="color: #808080">Hello {{$users->email}}</h4>
        </div>
        <div>
            <span style="line-height: 400%">
                Here’s a confirmation for your purchase for the Singapore Food Festival 2022.
            </span> <br>
            <p>
                We are happy to let you know that your order has been shipped! You can follow the status of your shipment by clicking on the button below. It could take some time for the tracking information to update. Do not hesitate to get in contact if you have any questions.
            </p>
            <div style="margin: 20px;">
                <a style="text-decoration: none;border: 1px solid;padding: 10px;background-color: #FF6E00;color: #fff;font-weight: bold;" href="#">Track Package</a>
            </div>
        </div>
        <div  style="margin-bottom: 50px;">
            <h1>Hungry for more? </h1>
            <span>Get updated on the <b>festival information</b>  and <b>explore the virtual marketplace</b> by visiting our <i>website</i>. Check out our social media for the <b>latest real foodie only happenings.</b></span>

            <div style="margin: 20px;">
                <a style="text-decoration: none;border: 1px solid;padding: 10px;background-color: #FF6E00;color: #fff;font-weight: bold;" href="#">Visit Our New Website</a>
            </div>

            <span>You Might Also Be Interested In This</span>
        </div>
        <div>

            <table>
                <tr>
                    <td class="col">
                        <img src="{{ asset('images/food/masterclass-1a.JPG') }}"  alt="" width="150" height="auto">
                    </td>
                    <td class="col">
                        <img src="{{ asset('images/food/masterclass-3a.JPG') }}"  alt="" width="150" height="auto">
                    </td>
                    <td class="col">
                        <img src="{{ asset('images/food/masterclass-2a.JPG') }}"  alt="" width="150" height="auto">
                    </td>
                </tr>
                <tr>
                    <td class="col">
                        <b>AUG 24, WED, 7PM SGT</b> <br>
                        <span>
                            Otak-Otak Udang by
                            Chef Daniel Sia
                        </span>
                    </td>
                    <td class="col">
                        <b>AUG 27, SAT, 4PM SGT </b> <br>
                        <span>
                            Masala Chai Pot de Crème
                            by Chef Muhammad Kamal
                        </span>
                    </td>
                    <td class="col">
                        <b>AUG 31, WED, 7PM SGT</b> <br>
                        <span>
                            Featuring “Bergedil” Culurgiones with Prawn Head Sambal and XO Sauce Leeks by Chef Muhammad Imran
                        </span>
                    </td>
                </tr>

            </table>
        </div>
<br>

        <div style="margin: 20px;">
            <a style="text-decoration: none;border: 1px solid;padding: 10px;background-color: #FF6E00;color: #fff;font-weight: bold;" href="#">Check Out Our Masterclasses Sessions</a>
        </div>

    </div>

</div>

</body>
</html>
