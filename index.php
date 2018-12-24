<?php 

echo "PHP funciona";

?>
<script>
/*
    let get = fetch('api/empresa/');
    console.log(get);

    FK_NU_ID_RP	VR_MAO_OBRA	VR_MATERIAL	NU_QTDE	FK_NU_ID_ITEM
3	1.70	15.80	250.00	C101   
*/

    fetch("api/registroPrecoItem", {
        method: "POST",
        body: 'Item=C101&RegistroPreco=5&MaoDeObra=15.80&Material=250.00&Quantidade=10'
    }).then(response => response.json())
    .then(dados => {

            if(dados.id) {
                fetch("api/registroPrecoItem", {
                    method: "PUT",
                    body: 'Id='+ dados.id +'&MaoDeObra=19.00'
                }).then(response => response.json())
                .then(dados => {
                        console.log(dados);

                        fetch("api/registroPrecoItem", {
                            method: "DELETE",
                            body: 'Id='+ dados.id
                        }).then(response => console.log(response.json()));
                    }
                );
            }
        }
    );

    


    

    
</script>