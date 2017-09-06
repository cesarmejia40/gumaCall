<script>
    $(document).ready(function() {
        //$("#USN,#USI").hide();
        localStorage.setItem("USN", $("#USI").text());
        localStorage.setItem("USI", $("#USN").text());

        firebase.database().ref("USUARIOS").child(localStorage.getItem("USN")).update({
            isConect : 1
        });

        $('#tblcampanias,#tbl_camp_cliente').DataTable({
            "scrollCollapse": true,
            "info":    false,
            "lengthMenu": [[20,30,50,100,-1], [20,30,50,100,"Todo"]],
            "language": {
                "zeroRecords": "NO HAY RESULTADOS",
                "paginate": {
                    "first":      "Primera",
                    "last":       "Última ",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "lengthMenu": "MOSTRAR _MENU_",
                "emptyTable": "NO HAY DATOS DISPONIBLES",
                "search":     "BUSCAR"
            }
        });
        $("#cModal").click(function() {
            $("#outCall").openModal();
        });
        if (localStorage.getItem("isInit")=== "true") {
            localStorage.setItem("InitCronos", getDate());
            localStorage.setItem("isInit", false);
            live()
        }else{
            live();
        }
        function live(){
            control = setInterval(function(){

                $('#ttCall').text(calDate(localStorage.getItem("InitCronos"),getDate()));
                EarEyesOfGod(
                    localStorage.getItem("InitCronos"),
                    getDate(),
                    $('#ttCall').text(),
                    localStorage.getItem("USN"),
                    localStorage.getItem("USI")
                );
            },10);
        }

    });

    function EarEyesOfGod(Init,End,isTime,id,name) {
        firebase.database().ref("USUARIOS").child(id).update({
            agent: id,
            dInit: Init,
            dEnd: End,
            ttConnect:isTime,
            name:name,
            Camp:"camp"
        });
    }
    function CloseEyesOfGod(id) {
        firebase.database().ref("USUARIOS").child(id).update({
            isConect : 0
        });
    }

    function Death() {
        clearInterval(control);
        //localStorage.setItem("InitCronos", "00-00-0000 00:00:00");
        localStorage.setItem("isInit", true);
        CloseEyesOfGod(localStorage.getItem("USN"));
        $('#ttCall').text("00:00:00");
        window.location.href = "salir"
    }

    function getDate(){
        var hoy = new Date();
        var dd = hoy.getDate(),mm = hoy.getMonth()+1,yyyy = hoy.getFullYear();
        var h = hoy.getHours(),i = hoy.getMinutes(),s=hoy.getSeconds();
        if(dd<10) {dd='0'+dd}
        if(mm<10) {mm='0'+mm}
        if(h<10) {h='0'+h}
        if(i<10) {i='0'+i}
        if(s<10) {s='0'+s}
        hoy = dd+'-'+mm+'-'+yyyy+ ' ' + h +':'+i+':'+s;
        return hoy;
    }
    function calDate(DateInit,DateNow){
        return(moment.utc(moment(DateNow,"DD-MM-YYYY HH:mm:ss").diff(moment(DateInit,"DD-MM-YYYY HH:mm:ss"))).format("HH:mm:ss"))
    }
    function cOut(){
        swal({
            title: '¿Desea Salir del sistema?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then(function () {
            Death();

        })

    }

        $("#cModal").click(function() { $("#outCall").openModal(); });
    
        Highcharts.chart('container-grafica', {
            chart: {
                type: 'line'
            },
            title: {
                text: ''
            },
            xAxis: {
                //categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            series: [{
                name: 'META',
                data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
                }, {
                name: 'REAL',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
                }]
            });
        });

        /*CONFIGURACION DE DATEPICKER*/
        $('.datepicker').pickadate({
            labelMonthNext: 'Mes siguiente',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            weekdaysLetter: ['D', 'L', 'M', 'X', 'J', 'V', 'S'],
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Cerrar',
            format: 'yyyy-mm-dd'
        });
</script>