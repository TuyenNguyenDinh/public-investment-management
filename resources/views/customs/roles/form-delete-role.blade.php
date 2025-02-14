<!-- Delete User Form -->
<div class="hidden" id="deleteUser">
   <form id="deleteRoleForm" class="row pt-2" method="POST">
      @csrf
      @method('DELETE')
      <input type="hidden" value="Go">
   </form>
</div>
<!--/ Delete User Form -->
