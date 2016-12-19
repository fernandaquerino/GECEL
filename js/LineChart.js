
    
function drawLineChart(idDiv, idLC, data, domainMin, domainMax){
    var ConsumoMax = 0;
    
    d3.select('#' + idLC).html('');

    data.forEach(function(element, index, array){
       if(element[1] > ConsumoMax)
           ConsumoMax = element[1];
    });
    ConsumoMax += 20;    

    var margin = {top: 20, right: 30, bottom: 30, left: 40},
        width = 600 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

    var x = d3.scale.linear()
        .domain([domainMin, domainMax])
        .range([0, width]);

    var y = d3.scale.linear()
        .domain([0, ConsumoMax])
        .range([height, 0]);

    var xAxis = d3.svg.axis()
        .ticks(domainMax)
        .scale(x)
        .orient("bottom");

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var line = d3.svg.line()
        .interpolate("monotone")
        .x(function(d) { return x(d[0]); })
        .y(function(d) { return y(d[1]); });

    var svg;    
    if(!$('#'+idLC).attr('id')){
         svg = d3.select(idDiv).append("svg")         
        .datum(data)
        .attr('id', idLC)
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    }else{
        svg = d3.select('#'+idLC)
        .datum(data)        
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    }

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis);

    svg.append("path")
        .attr("class", "line")
        .attr("d", line);

    svg.selectAll(".dot")    
        .data(data)
        .enter()
        .append("circle")
        .attr("class", "dot")
        .attr("cx", line.x())
        .attr("cy", line.y())
        .attr("fill", 'white')
        .attr("r", 5);

    var cont = 0;
    
    svg.selectAll('.dot').each(function(d,x){
        var _dot = d3.select(this);        
         
        _dot
        .attr('data-dia',data[cont][0])
        .attr('data-valor',data[cont][1])
        .on("click", function(){
            d3.select(this.parentNode)
              .selectAll('.dot')
              .classed('selected', false);
      
            d3.select(this)
                .classed('selected', true);
        
           

            d3.select('#h-dia').attr('value',d3.select(this).attr('data-dia'));
            updateProx(idLC, null);    
        });
        cont++;
    });
}

