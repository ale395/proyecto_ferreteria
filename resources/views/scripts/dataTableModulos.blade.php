<script type="text/javascript">
    $('#tableModulo').DataTable({
    	"processing": true,
        "serverSide": true,
        "ajax": "{{ route('api.modulos') }}",
        "columns": [
        	{data: 'modulo', name: 'modulo'},
        	{data: 'descripcion', name: 'descripcion'},
        	{data: 'acciones', name: 'acciones', orderable: false, searchable: false}
        ]});
</script>