<dialog id="import_teachers_modal" class="modal">
	<div class="modal-box w-11/12 max-w-xl">
		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
		</form>

		<h3 class="font-bold text-lg">Import Teachers CSV</h3>
		<p class="text-sm text-gray-500 mt-1 mb-4">
			Upload a CSV file with headers: <span class="font-semibold">code, first_name, middle_name, last_name, email, status</span>.
			Status is optional (defaults to <span class="font-semibold">active</span>).
		</p>

		<!-- <div class="mb-4">
			<a href="{{ route('registrar.teacher.import.sample') }}" class="link link-primary">
				Download Sample CSV
			</a>
		</div> -->

		<form action="{{ route('registrar.teacher.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
			@csrf
			<div class="form-control">
				<label class="label">
					<span class="label-text">Teachers CSV</span>
				</label>
				<input type="file" name="teachers_csv" accept=".csv,text/csv" class="file-input file-input-bordered w-full" required>
			</div>

			<div class="modal-action">
				<button type="button" class="btn btn-ghost" onclick="document.getElementById('import_teachers_modal').close();">Cancel</button>
				<button type="submit" class="btn btn-primary">Import</button>
			</div>
		</form>
	</div>
	<form method="dialog" class="modal-backdrop">
		<button>close</button>
	</form>
</dialog>
