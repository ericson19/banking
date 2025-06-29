const iacc_num = document.getElementById("iacc_num");
const ishowName = document.getElementById("ishowName");

iacc_num.addEventListener("keyup", async () => {
    console.log("pressed");
    
    if (iacc_num.value.length == 10) {
      try {
        const req = await fetch("../resolve_inbank.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `num=${iacc_num.value}`
        })
        const res = await req.json();
        if (res.status) {
          ishowName.value = `${res.name}`;
        
        }
      } catch (error) {
        ishowName.value = "Error retrieving info";
        console.error(error);
      }
    } else {
      ishowName.value = "";
    }
  })