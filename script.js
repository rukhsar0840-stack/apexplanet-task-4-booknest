document.querySelectorAll(".toggle-pass").forEach(btn=>{
  btn.addEventListener("click",()=>{
    const input=document.getElementById(btn.dataset.target);
    if(!input)return;
    input.type=input.type==="password"?"text":"password";
    btn.querySelector("i").className=input.type==="password"?"bi bi-eye":"bi bi-eye-slash";
  });
});
document.querySelectorAll("form").forEach(form=>{
  form.addEventListener("submit",e=>{
    if(!form.checkValidity()){e.preventDefault();e.stopPropagation();}
    form.classList.add("was-validated");
  });
});
