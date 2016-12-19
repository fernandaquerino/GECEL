function updateProx(id, valor){
        switch(id){
            case 'HgAno':
                $.post( "/GECEL/dashboard/Dashboard/obterConsumoMes",
                    { 
                        ano : valor
                    },
                    function(data) {
                        var sF = data.map(function(d){return [d.mes,d.consumo,d.preco];});
                        drawHG('#dashboard',sF,'HgMes');   
                        d3.select('#h-ano').attr('value',valor);
                    },
                    "json");                
                
            break;
            case 'HgMes':
                $.post( "/GECEL/dashboard/Dashboard/obterConsumoMesDia",
                    { 
                        mes : valor
                    },
                    function(data) {                        
                        var _domainMin = 1;
                        var _domainMax = 31;
                        drawLineChart('#dashboard', 'lcNoMes',data, _domainMin, _domainMax);                                              
                        d3.select('#h-mes').attr('value',valor);
                    },
                    "json");  

              
            break;    
            case 'lcNoMes':                
                $.post( "/GECEL/dashboard/Dashboard/obterConsumoDia",
                    { 
                        ano : d3.select('#h-ano').attr('value'),
                        mes : d3.select('#h-mes').attr('value'),
                        dia : d3.select('#h-dia').attr('value')
                    },
                    function(data) {                        
                        if(d3.select('#divConsumoDia')[0][0] == null){
                            d3.select('#dashboard')
                                .append('div')
                                .attr('id','divConsumoDia')
                                .append('table');
                        
                        }
                        
                        var tableConsumo = d3.select('#divConsumoDia').select('table');  
                        tableConsumo.html('');
                        
                        data.forEach(function(element, index, array){                            
                            var html = '<tr class="consumoLeitura">';
                            
                            html += '<td>'+element.Hora+'</td>'
                                   +'<td>'+element.PA_DCPONTOAPRESENTACAO+'</td>'
                                   +'<td>'+element.LE_QTCONSUMO+'Kw</td>'
                                   +'<td>R$ '+element.preco+'</td></tr>';
                            
                            tableConsumo.html(tableConsumo.html() + html);
                        });                        
                    },
                    "json"); 
            break;
        }
}

$(document).ready(function(){   
   $.post( "/GECEL/dashboard/Dashboard/obterConsumoAno",
        { 
            args : ''
        },
        function(data) {
            var sF = data.map(function(d){return [d.ano,d.consumo,d.preco];});
            var HgAno = drawHG('#dashboard',sF,'HgAno');
        },
        "json");    
});
