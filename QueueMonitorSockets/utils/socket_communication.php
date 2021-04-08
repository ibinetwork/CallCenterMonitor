<?php


function getSocketKey($res){

    $socket_key = "null";
    $parts = explode("\n", $res);
    if(count($parts) > 0){
        
        for($i=0;$i<count($parts);$i++){
            $element = $parts[$i];
            $found = strpos($element, "Sec-WebSocket-Key");
            if($found !== false){
                $key_value = explode(":", $element);
                $socket_key = trim($key_value[1]);
            }
            else{
                // echo "NOT FOUND: " . $element; 
            }
            
        }
        
        
    }

    return $socket_key;
}



function xor_b($bytes, $keys) {

    $decoded = array();

    // Iterate through each character
    for($i=0; $i<count($bytes); $i++ )
    {
        $byte_in = $bytes[$i];
        $byte_key = $keys[$i % 4];
        $byte_result = "";
        for($j=0;$j<strlen($byte_in);$j++){
            $byte_result .= intval(substr($byte_in,$j,1)) ^ intval(substr($byte_key, $j, 1));
        }
        // echo "\n" . $bytes[$i]  . " with " . $keys[$i % 4] . " equal to $byte_result\n";
        array_push($decoded, bindec($byte_result));
    }

    return $decoded;
}



function completeByte($byte, $max){
    
    $part = "";
    $rest = $max - strlen($byte);
    for($i=0; $i<$rest;$i++){
        $part = $part . "0";
    }
    
    return $part . $byte;
    
}

function decodeInputMessage($data){
  
    $byte_array = unpack('C*', $data);
    $fin_and_opcode = decbin($byte_array[1]);
    $is_message_complete = intval(substr($fin_and_opcode, 0, 1)) == 1;
    $type = substr($fin_and_opcode, -4, 8);
    if($type == "0001"){
        $type = "string";
    }
    else{
        $type = "unknown";   
    }

    $mask_and_length_indicator = decbin($byte_array[2]);
    $is_encoded_with_mask = intval(substr($mask_and_length_indicator, 0, 1)) == 1;
    $length_indicator = $byte_array[2] - 128;
    $length = -1;
    $index_reference = 3;
    if($length_indicator <= 125)
    {
        $length = $length_indicator;
    }
    else if($length_indicator == 126){
        $bits16 = completeByte(decbin($byte_array[$index_reference++]), 8) . completeByte(decbin($byte_array[$index_reference++]), 8);
    // echo "\nbit16" . $bits16;
        $length = bindec($bits16);
    }
    else{
        $bits64 = "";
        for($i=0;$i<8;$i++){
            $bits64 .= completeByte(decbin($byte_array[$index_reference++]), 8);
        }
        //echo "\nbit64: " . $bits64;
        $length = bindec($bits64);
    }


    $keys = array();
    for($i=0;$i<4;$i++){
        array_push($keys, completeByte(decbin($byte_array[$index_reference++]), 8));
    }

    $encoded = array();
    for($i=0;$i<$length;$i++){
        array_push($encoded, completeByte(decbin($byte_array[$index_reference++]), 8));
    }


    $decoded = xor_b($encoded, $keys);
    // $decoded[0] = bindec($decoded[0]);
    // var_export($decoded);

    $final_string = "";
    for($i=0;$i<count($decoded);$i++){
        $final_string .= pack('c*', $decoded[$i]);
    }


    return $final_string;


}

function encodeOutputMessage($final_string){

    $fin_and_opcode = "10000001";  // complete and string
    $bytes_to_send = strlen($final_string); 


    $extra_bytes_merge = "";
    if($bytes_to_send <= 125){
        $mask_and_length_indicator = completeByte(decbin($bytes_to_send), 8);  // server dont set mask bit
    }
    else if($bytes_to_send <= 65535){
        $mask_and_length_indicator = completeByte(decbin(126), 8);
        $extra_bytes_merge = completeByte(decbin($bytes_to_send), 16);
    }
    else{
        $mask_and_length_indicator = completeByte(decbin(127), 8);
        $extra_bytes_merge = completeByte(decbin($bytes_to_send), 64);
    }




    $frame = array();
    array_push($frame, bindec($fin_and_opcode));
    array_push($frame, bindec($mask_and_length_indicator));

    if(strlen($extra_bytes_merge) > 0){
        for($i=0; $i<intval(strlen($extra_bytes_merge)/8); $i++){
            array_push($frame, bindec(substr($extra_bytes_merge, $i*8, 8) ));
        }
    }

    for($i=0;$i<$bytes_to_send;$i++){
        array_push($frame, ord(substr($final_string,$i,1)));
    }

    //var_dump($frame);


    $output = "";
    for($i=0;$i<count($frame);$i++){
        $output .= pack('c*', $frame[$i]);
    }

    return $output;


}
