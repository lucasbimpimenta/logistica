class EmpresaLista {
    
    constructor() {
        
        this._empresas = [];
    }
    
    get empresas() {

        axios.get('/api/empresa')
        .then(function (response) {
            // handle success
            console.log(response);
            this._empresas = response.data
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .then(function () {
            // always executed
        });

        return [].concat(this._empresas);
    }
}