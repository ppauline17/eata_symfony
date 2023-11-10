document.getElementById('basic-addon1').addEventListener("click", ()=>{
    const input = document.getElementById('password');
    const eye = document.getElementById('eye');
    const eyeSlash = document.getElementById('eyeSlash');
    if(input.type=="text"){
        input.type="password";
        eye.classList.remove('d-none');
        eyeSlash.classList.add('d-none');
    }else{
        input.type="text";
        eye.classList.add('d-none');
        eyeSlash.classList.remove('d-none');
    }
})

