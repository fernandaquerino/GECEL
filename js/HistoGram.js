function drawHG(id, fD, idHg){
    var barColor = 'steelblue';    

    // compute total for each.
    var hG={},    hGDim = {t: 60, r: 0, b: 30, l: 0};
        hGDim.w = 500 - hGDim.l - hGDim.r, 
        hGDim.h = 300 - hGDim.t - hGDim.b;
        var hGsvg = '';    
            
        if($('#'+idHg).attr('id')){
            
            hGsvg = d3.select('#'+idHg).html('')
                .append("g")
                .attr("transform", "translate(" + hGDim.l + "," + hGDim.t + ")");
        }else{
            //create svg for histogram.
            hGsvg = d3.select(id)
                .append('div')
                .append("svg")
                .attr("width", hGDim.w + hGDim.l + hGDim.r)
                .attr("height", hGDim.h + hGDim.t + hGDim.b)
                .attr('id', idHg)
                .append("g")
                .attr("transform", "translate(" + hGDim.l + "," + hGDim.t + ")");
            
        }   
        
        // create function for x-axis mapping.
        var x = d3.scale.ordinal().rangeRoundBands([0, hGDim.w], 0.1)
                .domain(fD.map(function(d) { return d[0]; }));

        // Add x-axis to the histogram svg.
        hGsvg.append("g").attr("class", "x axis")
            .attr("transform", "translate(0," + hGDim.h + ")")
            .call(d3.svg.axis().scale(x).orient("bottom"));

        // Create function for y-axis map.
        var y = d3.scale.linear().range([hGDim.h, 0])
                .domain([0, d3.max(fD, function(d) { return d[1]; })]);

        // Create bars for histogram to contain rectangles and freq labels.
        var bars = hGsvg.selectAll(".bar").data(fD).enter()
                .append("g").attr("class", "bar");
        
        //create the rectangles.
        bars.append("rect")
            .attr("x", function(d) { return x(d[0]); })
            .attr("y", function(d) { return hGDim.h; })
            .attr("width", x.rangeBand())
            .attr("height", function(d) { return 0; })
            .attr('fill',barColor)
            .on("click",click);

        
        function click(d, index){                                     
            d3.select(this.parentNode.parentNode)
                    .selectAll('rect')
                    .classed('selected', false);
            
            d3.select(this).classed('selected', true);
            
            updateProx(idHg, d[0]);
        }        
        
        hG.update = function(){
            bars.select("rect").transition().duration(500)
                .attr("y", function(d) {return y(d[1]); })
                .attr("height", function(d) { return hGDim.h - y(d[1]); })
                .attr("fill", barColor);                
            
            //Create the frequency labels above the rectangles.
            bars.append("text").text(function(d){return d3.format(",")(d[1])})
                .attr("x", function(d) { return x(d[0])+x.rangeBand()/2; })
                .attr("y", function(d) { return y(d[1])-5; })
                .attr("text-anchor", "middle");

            bars.select("text").transition().duration(500)
                .text(function(d){return 'R$ '+ d3.format(",")(d[2]) + '\r\n ('+d[1]+'Kw)';})
                .attr("y", function(d) {return y(d[1])-5; });
        }
        
        hG.update();
        return hG;        
}