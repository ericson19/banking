async function fetchDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
        
           try {
            const response = await fetch("../payment/fetch_tran.php", {
              method: "POST",
              headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
              body: `startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}`
          })
          const data = await response.text();
          document.getElementById('transactionResults').innerHTML = data;
           } catch (error) {
            document.getElementById('transactionResults').innerHTML = error;
           }
}
async function fetchAmount() {
    const startAmount = document.getElementById('startAmount').value;
    const endAmount = document.getElementById('endAmount').value;
  
    
    const response = await fetch("fetch_tran.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
        body: `startAmount=${encodeURIComponent(startAmount)}&endAmount=${encodeURIComponent(endAmount)}`
    })
    const data = await response.text();
    document.getElementById('transactionResults').innerHTML = data;
}