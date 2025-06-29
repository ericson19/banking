const bank = document.getElementById("bank");
const acc_num = document.getElementById("acc_num");
const showName = document.getElementById("showName");

bank.addEventListener("change", async () => {
  if (acc_num.value.length !== 10) {
    showName.value = `Accunt number not correct`;
    return;
  }
  const request = await fetch("../resolve_account.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `bankCode=${bank.value}&accNum=${acc_num.value}`,
  });
  const data = await request.json();
if (data.status) {
  showName.value = `${data.data.account_name}`;
  showName.style.fontWeight = "bolder";
  console.log(data.data.account_name);
} else {
  showName.value = data.message
}
  
});


