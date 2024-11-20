document.body.onload=function() {
    nbr=3;
    p=0;
    container=document.getElementById("container");
    prev=document.getElementById("prev");
    next=document.getElementById("next");
    container.style.width=(800*nbr)+"px";
    for(i=1;i<=nbr;i++) {
        div=document.createElement("div");
        div.className = "image";
        div.style.backgroundImage="url('images/image"+i+".jpg')";
        container.appendChild(div);
    }
}

prev.onclick=function(){
    p++;
    if(p > 0) {
        p = -(nbr - 1);
    }
    container.style.transform="translate("+p*800+"px)";
}

next.onclick=function(){
    p--;
    if(p < -(nbr - 1)) {
        p = 0;
    }
    container.style.transform="translate("+p*800+"px)";
}