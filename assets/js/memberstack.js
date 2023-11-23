import memberstackDOM from '@memberstack/dom';

const memberstack = memberstackDOM.init({
    publicKey: "pk_sb_e0942cc2b16bdc26394a",
});

const loginButton = document.getElementById('login');
const signUpButton = document.getElementById('signup');

if (loginButton) {
    loginButton.addEventListener('click', function() {
        memberstack.openModal("LOGIN", {
            signup: {
                plans:["pln_free-zg4u0745"]
            }
        }).then(result => {
            login(result.data.tokens.accessToken)
        });

    });
}

if (signUpButton) {
    signUpButton.addEventListener('click', function() {
        memberstack.openModal("SIGNUP").then(result => {
            login(result.data.tokens.accessToken)
        });
    });
}

const login = (accessToken) => fetch('/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ token: accessToken }),
}).then(response => {
    if (!response.ok) {
        throw new Error('La risposta della rete non era ok');
    }
    return response.json();
}).then(data => {
    memberstack.hideModal();
    if (data.appUrl) {
        window.location = data.appUrl;
    }
}).catch(error => {
    console.error('Si è verificato un problema con la tua richiesta fetch:', error);
});