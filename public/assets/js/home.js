window.onload = function() {
    let form = document.querySelector("form");
    form.onsubmit=this.rechercher;
}

function rechercher(e) {
    e.preventDefault();

    let form = e.target; 
    let deb = form.elements.dtdeb.value;
    let fin = form.elements.dtfin.value;
    let com = form.elements.cbocomm.value;
    let cli = form.elements.cboclient.value;

    let data = {
        deb:deb,
        fin:fin,
        com:com,
        cli:cli
    };
    
    let url=e.target.action; 
    const request = new Request(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' },
    });

    fetch(request)
    .then(resp => resp.json())
    .then(data => {
        afficher(data);
    });
}

function afficher(data) {
    let tFrais = document.getElementById("tFrais");
    tFrais.textContent ="";

    if(data.length <1) {
        tFrais.innerHTML ="<br/><p>Aucun frais</p>";
    }

    data.forEach(t => {
        let tr = document.createElement("tr");
        tr.appendChild( getTd(t.dtjourStr) );
        tr.appendChild( getTd(t.clientStr) );
        tr.appendChild( getTd(t.commercialStr) );
        tr.appendChild( getTd(t.typeFrais) );
        tr.appendChild( getTd(t.montant) );
        tr.appendChild( getTd(t.quantite) );
        tr.appendChild( getTd(t.invites) ); 
        tFrais.appendChild(tr);
    });
}

function getTd(text) {
    let td = document.createElement("td");
    td.textContent = text;
    return td;
}