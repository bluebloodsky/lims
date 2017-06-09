<?php

/**
 * Created by PhpStorm.
 * User: libgcc
 * Date: 2016/12/26
 * Time: 15:26
 */
class FileListModel
{
    static public function GetFileList()
    {
        $RET['logFiles'] = self::GetLogFileList();
        $RET['cfgFiles'] = self::GetCfgFileList();
        $RET['icdFiles'] = self::GetIcdFileList();

        return $RET;
    }

    static public function GetLogFileList()
    {
        global $gMmsHome;
        $logdirAndFile = gGetLogDirAndFileName();
        $logDir = $logdirAndFile['logDir'];
        chdir($gMmsHome);
        return self::GetFilesPrivate("log", "log", $logDir);
        return  $RET;
    }

    static public function GetCfgFileList()
    {
        global $gMmsEtcHome;
        return self::GetFilesPrivate("cfg", "sqlite3", $gMmsEtcHome);
    }

    static public function GetIcdFileList()
    {
        global $gMmsIcdHome;
        return self::GetFilesPrivate("icd", "icd", $gMmsIcdHome);
    }

    static private function GetFilesPrivate($type, $suffix, $dir)
    {
        if(!chdir($dir))
        {
            echo "chdir '$dir' failed...";
            throw new Exception("chdir $dir failed");
        }
        $dh = dir(".");
        $RET=[];
        while($entry = $dh->read())
        {
            if($entry === "." || $entry === "..")
                continue;
            if(!is_file($entry) || !preg_match("/.$suffix$/", $entry))
                continue;

            $info = stat($entry);
            $RET[] = [
                'name'=> $entry,
                'size'=> $info['size'],
                'uptime'=> $info['mtime'],
                'url' => "file_download?type=$type&filename=$entry"];
        }
        return $RET;
    }

    static public function GetFile($type, $filename, $resp)
    {
        global $gMmsHome, $gMmsCfgHome, $gMmsIcdHome;

        $dir = '';
        if($type == "log")
        {
            chdir($gMmsHome);
            $dir = gGetLogDirAndFileName()['logDir'];
        }
        else if($type == "cfg")
            $dir = $gMmsCfgHome;
        else if($type == "icd")
            $dir = $gMmsIcdHome;

        return self::GetFilePrivate($dir, $filename, $resp);
    }

    static private function GetFilePrivate($dir, $filename, $resp)
    {
        if(!chdir($dir))
        {
            echo "chdir '$dir' failed...";
            throw new Exception("chdir $dir failed");
        }

        if(!is_file($filename))
            throw new Exception("now such file $filename");

        $info = stat($filename);
        $fp = fopen($filename, 'r');
        if(!$fp)
            throw new Exception("unable to open file $filename!");

        $stream = new \Slim\Http\Stream($fp); // create a stream instance for the response body
        return $resp->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', "attachment; filename=$filename")
            ->withBody($stream); // all stream contents will be sent to the response
    }
}