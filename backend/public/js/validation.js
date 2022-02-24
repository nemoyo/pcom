const yearExp = /\d{4}/;
const monthExp = /(0[1-9]|1[0-2])/;

const askYear = document.getElementById("ask-year");
const errMsgAskYear = document.getElementById("err-msg-ask-year");
const askMonth = document.getElementById("ask-month");
const errMsgAskMonth = document.getElementById("err-msg-ask-month");

askYear.addEventListener("keyup", e => {
  if (yearExp.test(askYear.value)){
    askYear.setAttribute("class", "input-invalid");
    askYear.classList.remove("input-invalid");
    errMsgAskYear.style.display="none";
  } else {
    askYear.setAttribute("class", "input-invalid");
    errMsgAskYear.style.display="inline";
  }
});

askMonth.addEventListener("keyup", e => {
  if (monthExp.test(askMonth.value)){
    askMonth.setAttribute("class", "input-invalid");
    askMonth.classList.remove("input-invalid");
    errMsgAskMonth.style.display="none";
  } else {
    askMonth.setAttribute("class", "input-invalid");
    errMsgAskMonth.style.display="inline";
  }
});
