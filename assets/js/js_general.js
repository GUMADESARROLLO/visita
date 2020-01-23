$('.dropdown-toggle').dropdown();

// Sidebar toggle behavior
$('#sidebarCollapse').on('click', function() {
	$('#sidebar, #content').toggleClass('active');
});

/*PARA MOSTRAR ALERTAS*/
function mensaje(mensaje, tipo) {
    /*
    Tipos:
    success, error, warning, info, question
    +*/
    const toast = swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    toast({
      type: tipo,
      title: mensaje
    })
}


/*if (screen.width<=412 || screen.width<=869) {
  $("table").addClass('table-responsive');
  
}*/

