function paymentGateWay() {
  fetch("/wellbe/public/patient/generatehash", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
    },
    body: new URLSearchParams({
      order_id: "12345",
      doc_id: sessionStorage.getItem("doc_id"),
    }),
  })
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      }
    })
    .then((obj) => {
      try {
        payhere.onCompleted = function (orderId) {
          console.log("Payment completed. OrderID: " + orderId);
          console.log("Redirecting to return URL...");
          window.location.href = `http://localhost/wellbe/public/patient/patient_dashboard.php`;
        };

        // PayHere payment dismissed callback
        payhere.onDismissed = function () {
          console.log("Payment dismissed");
          // Handle the case where the user dismisses the payment window.
        };

        // PayHere error callback
        payhere.onError = function (error) {
          console.log("Payment error: " + error);
          // Handle errors, show an error page, or retry.
        };

        // Prepare the payment object using the data from the response
        var payment = {
          sandbox: true, // Set to false for live mode
          merchant_id: "1228628", // Replace with your PayHere Merchant ID
          return_url:
            "http://localhost/wellbe/public/patient/patient_dashboard.php", // Your return URL
          cancel_url: "http://localhost/wellbe/hello.php", // Your cancel URL
          notify_url: "https://wellbe.loca.lt/wellbe/public/payment/getPaymentData", // Your backend URL for payment notifications
          order_id: obj["order_id"],
          items: obj["items"],
          amount: obj["amount"],
          currency: obj["currency"],
          hash: obj["hash"], // Replace with hash generated on the backend
          first_name: obj["first_name"],
          last_name: obj["last_name"],
          email: obj["email"],
          phone: obj["phone"],
          address: obj["address"],
          city: obj["city"],
          country: obj["country"],
          delivery_address: obj["delivery_address"],
          delivery_city: obj["delivery_city"],
          delivery_country: obj["delivery_country"],
          custom_1: JSON.stringify({
            appointment_id: sessionStorage.getItem("appointment_id"),
            doc_id: sessionStorage.getItem("doc_id"),
            date: sessionStorage.getItem("day"),
            patient_id: obj["patient_id"],
          }),//appointment id
          custom_2: "",
        };

        // Trigger the payment window
        payhere.startPayment(payment);
      } catch (error) {
        console.error("Error processing payment: ", error);
        alert("There was an error with the payment process.");
      }
    });
}
//     var xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//         if (xhttp.readyState === 4 && xhttp.status === 200) {
//             try {
//                 // Parse the response JSON object
//                 var obj = JSON.parse(xhttp.responseText);
//                 console.log(obj); // Debugging response

//                 const urlParams = new URLSearchParams(window.location.search);
//                 const nic = urlParams.get('nic') || obj.nic; // Fallback to response 'nic'

//                 payhere.onCompleted = function(orderId) {
//                     console.log("Payment completed. OrderID: " + orderId);
//                     console.log("Redirecting to return URL...");
//                     window.location.href = `http://localhost/Appointment/patient_dashboard.php`;
//                 };

//                 // PayHere payment dismissed callback
//                 payhere.onDismissed = function() {
//                     console.log("Payment dismissed");
//                     // Handle the case where the user dismisses the payment window.
//                 };

//                 // PayHere error callback
//                 payhere.onError = function(error) {
//                     console.log("Payment error: " + error);
//                     // Handle errors, show an error page, or retry.
//                 };

//                 // Prepare the payment object using the data from the response
//                 var payment = {
//                     "sandbox": true,                          // Set to false for live mode
//                     "merchant_id": "1228628",                 // Replace with your PayHere Merchant ID
//                     "return_url": "http://localhost/Appointment/patient_dashboard.php?payment=success&nic=${nic}", // Your return URL
//                     "cancel_url": "http://localhost/Appointment/hello.php", // Your cancel URL
//                     "notify_url": "http://sample.com/notify", // Your backend URL for payment notifications
//                     "order_id": obj["order_id"],
//                     "items": obj["items"],
//                     "amount": obj["amount"],
//                     "currency": obj["currency"],
//                     "hash": obj["hash"],// Replace with hash generated on the backend
//                     "first_name": obj["first_name"],
//                     "last_name": obj["last_name"],
//                     "email": obj["email"],
//                     "phone": obj["phone"],
//                     "address": obj["address"],
//                     "city": obj["city"],
//                     "country": obj["country"],
//                     "delivery_address": obj["delivery_address"],
//                     "delivery_city": obj["delivery_city"],
//                     "delivery_country": obj["delivery_country"],
//                     "custom_1": "",
//                     "custom_2": ""
//                 };

//                 // Trigger the payment window
//                 payhere.startPayment(payment);
//             } catch (error) {
//                 console.error("Error processing payment: ", error);
//                 alert("There was an error with the payment process.");
//             }
//         }
//     };

//     // Make the request to the server-side script
//     xhttp.open("GET", "./payhereprocess.php", true); //p.controller function url
//     xhttp.send();
// }
