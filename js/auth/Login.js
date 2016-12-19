$(document).ready(function(){
    
      
    $('#btnEntrar').click(function(){
        $.post( "/GECEL/usuario/Login/autenticar",
        { 
            usuario: $('#txtUsuario').val(), 
            senha: $('#txtSenha').val() 
        },
        function(data) {
            if(data.ID > 0){
                top.location = '/GECEL/dashboard/Dashboard';
            }else{
                alert('Usuario ou senha incorretos');            
            }
        },
        "json");       
        
    });
    
});