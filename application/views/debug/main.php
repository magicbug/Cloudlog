<div class="container debug_main">

<h2><?php echo $page_title; ?></h2>

<div class="row">
    <div class="col">

        <div class="card">
            <div class="card-header">Server Information</div>
            <div class="card-body">
                <table width="100%">
                    <tr>
                        <td>Server Software</td>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                    </tr>

                    <tr>
                        <td>PHP Version</td>
                        <td><?php echo phpversion(); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Folder Perimissions</div>
            <div class="card-body">
                <p>This checks the folders Cloudlog uses are read and writeable by PHP.</p>
                <table width="100%">
                    <tr>
                        <td>/backup</td>
                        <td>
                            <?php if($backup_folder == true) { ?>
                                <span class="badge badge-success">Success</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">Failed</span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>/updates</td>
                        <td>
                            <?php if($updates_folder == true) { ?>
                                <span class="badge badge-success">Success</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">Failed</span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>/uploads</td>
                        <td>
                            <?php if($uploads_folder == true) { ?>
                                <span class="badge badge-success">Success</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">Failed</span>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">PHP Modules</div>
            <div class="card-body">
                <table width="100%">
                    <tr>
                        <td>curl</td>
                        <td>
                            <?php if(in_array  ('curl', get_loaded_extensions())) { ?>
                                <span class="badge badge-success">Installed</span>
                            <?php } else { ?> 
                                <span class="badge badge-danger">Not Installed</span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>MySQL</td>
                        <td>
                            <?php if(in_array  ('mysqli', get_loaded_extensions())) { ?>
                                <span class="badge badge-success">Installed</span>
                            <?php } else { ?> 
                                <span class="badge badge-danger">Not Installed</span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>mbstring</td>
                        <td>
                            <?php if(in_array  ('mbstring', get_loaded_extensions())) { ?>
                                <span class="badge badge-success">Installed</span>
                            <?php } else { ?> 
                                <span class="badge badge-danger">Not Installed</span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>xml</td>
                        <td>
                            <?php if(in_array  ('xml', get_loaded_extensions())) { ?>
                                <span class="badge badge-success">Installed</span>
                            <?php } else { ?> 
                                <span class="badge badge-danger">Not Installed</span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td>openssl</td>
                        <td>
                            <?php if(in_array  ('openssl', get_loaded_extensions())) { ?>
                                <span class="badge badge-success">Installed</span>
                            <?php } else { ?> 
                                <span class="badge badge-danger">Not Installed</span>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

</div>