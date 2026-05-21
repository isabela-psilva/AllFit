function exibirModal(titulo, mensagem, urlDestino = null, campoFocus = null) {
    document.getElementById('modalTitulo').innerText = titulo;
    document.getElementById('modalTexto').innerText = mensagem;
                
    const btnOk = document.getElementById('btnModalOk');
    const modal = document.getElementById('meuModal');

    btnOk.onclick = function() {
        modal.style.display = 'none';
                    
        if (urlDestino) {
            window.location.href = urlDestino;
        } else if (campoFocus) {
            campoFocus.focus();
        }
    };

    modal.style.display = 'flex';
}

function fazerLogout() {
    localStorage.removeItem('usuarioLogado');
    exibirModal("Informação", "Logout realizado com sucesso.", "login.html");
    console.log("logout feito, fi");
}

function calcularSugestao(imc) {
    let sugestao = "";
    if (imc < 18.5) {
        sugestao = "Ganhar massa";
    } else if (imc < 25) {
        sugestao = "Manter alimentação saudável";
    } else {
        sugestao = "Emagrecer";
    }
    return sugestao;
}