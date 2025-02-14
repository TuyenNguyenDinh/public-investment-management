<!-- Delete Permission Form -->
<div class="hidden" id="deletePermission">
   <form id="deletePermissionForm" class="row pt-2" method="POST">
      @csrf
      @method('DELETE')
      <input type="hidden" value="Go">
   </form>
</div>
<!--/ Delete Permission Form -->
