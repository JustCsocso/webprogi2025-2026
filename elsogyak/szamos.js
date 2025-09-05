function feladat1(){
    const elso=document.getElementById("szam1");
    const mas=document.getElementById("szam2");

    
    const koztes=[];
    for(let i=elso+1;i<mas;i++){
        koztes.push(i);
    }

    document.getElementById("szias").innerHTML=koztes;
}