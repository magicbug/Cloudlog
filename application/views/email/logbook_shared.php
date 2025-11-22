Hi <?php echo $user_callsign; ?>,

<?php echo $owner_callsign; ?> has shared a logbook with you on Cloudlog.

Logbook: <?php echo $logbook_name; ?>

Permission Level: <?php echo ucfirst($permission_level); ?>

<?php if ($permission_level == 'read') { ?>
You can now view this logbook and its QSOs.
<?php } elseif ($permission_level == 'write') { ?>
You can now view, add, edit, and delete QSOs in this logbook, as well as manage station locations.
<?php } elseif ($permission_level == 'admin') { ?>
You have full administrative access to this logbook, including the ability to manage other users' access.
<?php } ?>

Getting Started:
<?php if ($permission_level != 'read') { ?>
1. Log into your Cloudlog account at: <?php echo $base_url; ?>

2. Create a Station Location using the shared callsign (<?php echo $owner_callsign; ?>):
   - Go to Station Locations and click "Create Station Location"
   - Enter the callsign: <?php echo $owner_callsign; ?>
   - Enter the location details (gridsquare, DXCC, etc.)
   - Save the station location

3. Link your Station Location to this logbook:
   - Go to Logbooks > Edit "<?php echo $logbook_name; ?>"
   - In the "Link Location" section, select your newly created station location
   - Click "Link Location"

4. You're ready to start logging! Set this logbook as active from the Logbooks page.
<?php } else { ?>
1. Log into your Cloudlog account at: <?php echo $base_url; ?>

2. Go to Logbooks and set "<?php echo $logbook_name; ?>" as your active logbook to view the QSOs.
<?php } ?>

If you have any questions, please contact <?php echo $owner_callsign; ?>.

Regards,

Cloudlog.
