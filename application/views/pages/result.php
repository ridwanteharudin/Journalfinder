  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Result</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    <table id="example" class="display" style="width:100%">
      <thead>
        <tr>
          <th scope="col">Match</th>
          <!-- <th scope="col">Id Direktori</th> -->
          <th scope="col">Judul</th>
          <th scope="col">Penerbit</th>
          <!-- <th scope="col">Alamat</th> -->
          <!-- <th scope="col">Editor</th> -->
          <th scope="col">Deskriptor</th>
          <th scope="col">Website</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $key=>$list) { ?>
            <tr>
              <td><b><?php echo $list['match'];?> % </b></td>
              
              <td><?php echo $list['judul'];?></td>
              <td><?php echo $list['penerbit'];?></td>
              
              <td>
                <?php $iterasi = 1;
                  foreach ($list['deskriptor'] as $key) {
                    echo $iterasi.". ".$key."<br>";
                      $iterasi++;
                    };?>
              </td>
              <td><?php echo $list['action'];?></td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
    </div>
    <!-- /.box-body -->
  </div>
  <!--modal detail -->
</div>
</div>
  
