
/*
=======================================================================
Tom's Java Utils
Copyright (C) 2001  Thomas W. Aeby
All Rights Reserved

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

=======================================================================

Author: Thomas W. Aeby
E-Mail: tomae@sfi.ch

=======================================================================

@(#) $Id: Encoder.java,v 1.4 2004/02/04 08:10:35 aeby Exp $

Revision History:

$Log: Encoder.java,v $
Revision 1.4  2004/02/04 08:10:35  aeby
added MD5Crypt support

Revision 1.3  2002/10/13 11:56:05  aeby
added MD5 support

Revision 1.2  2001/03/13 16:36:59  aeby
added standard file headers

 
=======================================================================
*/

package com.graeff.pwencode;

import java.util.Vector;
import java.util.Enumeration;


public class Encoder {


static Vector encoders = new Vector();

static {
    new CryptEncoder();
    new NTEncoder( NTEncoder.NT_ENC );
    new NTEncoder( NTEncoder.LM_ENC );
    new MD5Encoder();
    new MD5CryptEncoder();
}

public static void register( EncoderImpl ei ) {
    if( ! encoders.contains( ei ) ) 
      encoders.addElement( ei );
}


public static EncoderImpl findEncoder( String encoding ) {
    Enumeration walker = encoders.elements();
    while( walker.hasMoreElements() ) {
	EncoderImpl ei = (EncoderImpl)walker.nextElement();
	if( ei.accepts( encoding ) ) return ei;
    }
    return null;
}


public static String encode( String encoding, String password ) {
    EncoderImpl ei = findEncoder( encoding );
    if( ei == null ) return null;
    return ei.encode( password );
}


public static boolean authorize( String encoding, String hash, String password ) {
    EncoderImpl ei = findEncoder( encoding );
    if( ei == null ) return false;
    return ei.authorize( hash, password );
}

public static void main( String args[] ) {
    System.out.println( encode( args[0], args[1] ) );
}


}
