
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

@(#) $Id: NTEncoder.java,v 1.2 2001/03/13 16:36:59 aeby Exp $

Revision History:

$Log: NTEncoder.java,v $
Revision 1.2  2001/03/13 16:36:59  aeby
added standard file headers

 
=======================================================================
*/

package com.graeff.pwencode;

public class NTEncoder extends EncoderImpl {

public final static int NT_ENC = 0;
public final static int LM_ENC = 1;

private int mode;


public NTEncoder( int what ) {
    Encoder.register( this );
    mode = what;
}


public boolean accepts( String encoding ) {
    return( encoding.equals( (mode==NT_ENC)?"nt":"lm" ) );
}


public static byte[] toMSUc( String text ) {
    byte result[] = new byte[ text.length() * 2 ];
    byte org[] = text.getBytes();
    for( int i=0; i<org.length; i++ ) {
        result[i*2+1] = 0;
	result[i*2] = org[i];
    }
    return result;
}


public String encode( String password ) {
    if( password.length() > 128 ) password = password.substring( 0, 128 );
    if( mode == NT_ENC ) {
	byte encoded[] = toMSUc( password );
	byte hashed[] = MD4.mdfour( encoded );
	StringBuffer res = new StringBuffer();
	for( int i=0; i<hashed.length; i++ ) {
	    String hex = Integer.toHexString( hashed[i] );
	    if( hex.length()<2 ) hex = "0" + hex;
	    res.append( hex.substring( hex.length()-2 ) );
	}
	return res.toString().toUpperCase();
    }
    else {
        if( password.length() > 14 ) password = password.substring( 0, 14 );
	return WinDES.E_P16( password.toUpperCase() );
    }
}
      


public static void main( String args[] ) {
    NTEncoder nte = new NTEncoder( NT_ENC );
    System.out.println( nte.encode( args[0] ) );
    nte = new NTEncoder( LM_ENC );
    System.out.println( nte.encode( args[0] ) );
}

}
