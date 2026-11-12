<?php
/*
	Run: php application/tests/Ses_mail_test.php
*/

define('BASEPATH', true);
require __DIR__ . '/../libraries/Ses_mail.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

$ses = new Ses_mail();

// AWS Signature Version 4 example: IAM ListUsers, 2015-08-30T12:36:00Z.
$signed = $ses->sign(
	'GET',
	'iam.amazonaws.com',
	'/',
	'Action=ListUsers&Version=2010-05-08',
	array(
		'content-type' => 'application/x-www-form-urlencoded; charset=utf-8',
		'host' => 'iam.amazonaws.com',
		'x-amz-date' => '20150830T123600Z',
	),
	'',
	'AKIDEXAMPLE',
	'wJalrXUtnFEMI/K7MDENG+bPxRfiCYEXAMPLEKEY',
	'us-east-1',
	'iam',
	'20150830T123600Z'
);
assert($signed['signature'] === '5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7', $signed['signature']."\n".$signed['canonical']);
assert(strpos($signed['authorization'], 'Credential=AKIDEXAMPLE/20150830/us-east-1/iam/aws4_request') !== false);
assert(strpos($signed['authorization'], 'SignedHeaders=content-type;host;x-amz-date') !== false);

$settings = array(
	'region' => 'eu-west-1',
	'access_key' => 'AKIAIOSFODNN7EXAMPLE',
	'secret_key' => 'wJalrXUtnFEMI/K7MDENG+bPxRfiCYEXAMPLEKEY',
	'from' => 'from@example.com',
	'from_name' => 'Cloudlog',
);

$captured = null;
$result = $ses->send($settings, array(
	'to' => 'to@example.com',
	'subject' => 'Hello',
	'body' => "Line one\nLine two",
	'reply_to' => 'reply@example.com',
	'reply_to_name' => 'G4ABC',
), function ($request) use (&$captured) {
	$captured = $request;
	return array(
		'status' => 200,
		'body' => '<SendEmailResponse xmlns="http://ses.amazonaws.com/doc/2010-12-01/"><SendEmailResult><MessageId>abc-123</MessageId></SendEmailResult></SendEmailResponse>',
		'error' => '',
	);
}, '20261112T140000Z');

assert($result['sent'] === true, $result['error']);
assert($result['message_id'] === 'abc-123');
assert($captured['url'] === 'https://email.eu-west-1.amazonaws.com/');
assert(strpos($captured['body'], 'Action=SendEmail') !== false);
assert(strpos($captured['body'], rawurlencode('"Cloudlog" <from@example.com>')) !== false);
assert(strpos($captured['body'], rawurlencode('"G4ABC" <reply@example.com>')) !== false);
assert(strpos($captured['body'], 'Destination.ToAddresses.member.1=to%40example.com') !== false);
assert(strpos($captured['headers'][2], 'AWS4-HMAC-SHA256 Credential=AKIAIOSFODNN7EXAMPLE/20261112/eu-west-1/ses/aws4_request') === 0 || strpos($captured['headers'][2], 'Credential=AKIAIOSFODNN7EXAMPLE/20261112/eu-west-1/ses/aws4_request') !== false);

$rejected = $ses->send($settings, array(
	'to' => 'to@example.com',
	'subject' => 'Hello',
	'body' => 'Body',
), function ($request) {
	return array(
		'status' => 400,
		'body' => '<ErrorResponse xmlns="http://ses.amazonaws.com/doc/2010-12-01/"><Error><Type>Sender</Type><Code>MessageRejected</Code><Message>Email address is not verified.</Message></Error></ErrorResponse>',
		'error' => '',
	);
}, '20261112T140000Z');
assert($rejected['sent'] === false);
assert($rejected['error'] === 'MessageRejected: Email address is not verified.');

$called = false;
$missing = $ses->send(array('region' => '', 'access_key' => '', 'secret_key' => '', 'from' => ''), array('to' => 'a@b.com'), function () use (&$called) {
	$called = true;
	return array('status' => 200, 'body' => '', 'error' => '');
});
assert($called === false);
assert($missing['sent'] === false);

$called = false;
$bad_region = $ses->send(array(
	'region' => 'eu-west-1.evil.example',
	'access_key' => 'AKIA',
	'secret_key' => 'secret',
	'from' => 'from@example.com',
), array('to' => 'to@example.com', 'subject' => 'Hi', 'body' => 'Hi'), function () use (&$called) {
	$called = true;
	return array('status' => 200, 'body' => '', 'error' => '');
});
assert($called === false);
assert($bad_region['error'] === 'Amazon SES region is not valid.');

echo "Ses_mail_test OK\n";
