	<!-- Main content -->
      <section class="content">

      	<div class="box-body">
              <form role="form" method="post" action="<?php echo base_url(); ?>Journalfinder/search">
                <!-- text input -->
                <div class="form-group">
                  <label>Judul</label>
                  <input name="judul" type="text" class="form-control" placeholder="Masukan judul disini" required>
                </div>

                <div class="form-group">
                  <label>Abstrak</label>
                  <textarea name="abstrak" class="form-control" placeholder="Masukan abstrak disini" rows="5" required></textarea>
                </div>

                <div class="form-group">
                  <label>Kata Kunci</label>
                  <input name="keyword" type="text" class="form-control" placeholder="Masukan kata kunci disini" required>
                </div>

                <!-- checkbox -->
                <div class="form-group">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="teknologi">
                      Teknologi
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="ekonomi">
                      Ekonomi
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="pertanian">
                      Pertanian
                    </label>
                  </div>

                   <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="matematika">
                      Matematika
                    </label>
                  </div>

                   <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="fisika">
                      Fisika
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="humaniora">
                      Humaniora
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="kimia">
                      Kimia
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="bisnis">
                      Bisnis
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="kesehatan">
                      Kesehatan
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="bidang[]" value="biologi">
                      Biologi
                    </label>
                  </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-md">
                        <span class="glyphicon glyphicon-floppy-disk">
                        </span>Search
                    </button>
                </div>

              </form>
            </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Harapan kami</h3>
          </div>
          <div class="box-body">
            <ul class="nav">
	          	<li>1. Meningkatkan kemudahan pencarian data jurnal dan artikel ilmiah di Indonesia</li>
	            <li>2. Meningkatkan kolaborasi penelitian melalui penyediaan informasi sitasi/pencuplikan terhadap suatu jurnal dan artikel</li>
	            <li>3. Meningkatkan kualitas jurnal dan artikel ilmiah melalui penilaian terhadap suatu jurnal melalui metoda : Impact Factor dan Citation Impact</li>
	            <li>4. Meningkatkan integrasi informasi jurnal dan artikel ilmiah dengan peningkatan efektifitas dan efisiensi sistem interoperabilitas jurnal ilmiah online dan ISJD LIPI</li>
	            <li>5. Meningkatkan layanan informasi Iptek Nasional melalui informasi visual sitasi jurnal dan artikel ilmiah, jaringan keilmuan (bidang ilmu, peneliti dan lembaga), serta kecenderungan dan pola perkembangan ilmu pengetahuan di Indonesia.</li>
	            <br>
          	</ul>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->


  