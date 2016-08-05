function check(){
         $.getJSON('checkprint.php', function(data) {
            if(data!=null) {
			if (data.type=="D")
			{
			var applet = document.getElementById('qz');
			applet.findPrinter(barprinter);
			applet.append(String.fromCharCode(27));
			applet.append(String.fromCharCode(112));
			applet.append(String.fromCharCode(48));
			applet.append(String.fromCharCode(55));
			applet.append(String.fromCharCode(121));
			applet.print();
			}
			else
			{
            var applet = document.getElementById('qz');
			if (data.type=="V" || data.type=="F" || data.type=="D") applet.findPrinter(barprinter);
			else applet.findPrinter(kitchenprinter);
			if (data.type=="V" || data.type=="F") {
			if (text1!="") applet.append(text1+"\r\n");
			if (text2!="") applet.append(text2+"\r\n");
			if (text3!="") applet.append(text3+"\r\n");
			applet.append("----------------------------------------\r\n");
			}
			if (data.type=="V") {
			if (data.ref==0) applet.append(voucher_label+" "+data.datetime);
			else applet.append(voucher_label+" "+table_label+":"+data.place+" "+data.datetime);
			}
			if (data.type=="F") {
			applet.append(label_bill+":"+data.ref+"\r\n"+data.datetime+" "+data.note);
			}
			if (data.type=="K") {
			applet.append("Pedido Mesa:"+data.place+" "+data.datetime);
			if(data.note!=undefined) applet.append("\r\n"+data.note);
			}
			applet.append("\r\n----------------------------------------\r\n");
			if(data.type!="K") applet.append("Descripcion            Cant.    Importe\r\n");
			else applet.append(header_lines+"\r\n");
			applet.append("----------------------------------------\r\n");
			$.each(data.lines, function(key, val) 
				{
				applet.append(val.label);
				applet.append(val.qty);
				if(data.type!="K") applet.append(val.total);
				applet.append("\r\n");
				if(val.note!=undefined) applet.append(val.note+"\r\n");
				});
			applet.append("----------------------------------------\r\n");
			if (data.type=="V" || data.type=="F") {
			applet.append(totalvat_label+": "+data.tva+"\r\n");
			applet.append(totalttc_label+": "+data.total+"\r\n");
			applet.append("----------------------------------------\r\n");
			applet.append(text4+"\r\n");
			}
			if (drawer==1) {
			applet.append(String.fromCharCode(27));
			applet.append(String.fromCharCode(112));
			applet.append(String.fromCharCode(48));
			applet.append(String.fromCharCode(55));
			applet.append(String.fromCharCode(121));
			}
			applet.append("\r\n\r\n\r\n\r\n\r\n\r\n");
			applet.append(String.fromCharCode(27));
			applet.append(String.fromCharCode(109));
			applet.print();
            }
			}
        });
}
