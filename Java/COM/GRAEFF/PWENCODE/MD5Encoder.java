
/*
=======================================================================
Tom's Java Utils
Copyright (C) 2002  Thomas W. Aeby
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

@(#) $Id: MD5Encoder.java,v 1.1 2002/10/13 11:56:05 aeby Exp $

Revision History:

$Log: MD5Encoder.java,v $
Revision 1.1  2002/10/13 11:56:05  aeby
added MD5 support

Revision 1.2  2001/03/13 16:36:59  aeby
added standard file headers

 
=======================================================================
*/

package com.graeff.pwencode;

public class MD5Encoder extends EncoderImpl {

public MD5Encoder() {
    Encoder.register( this );
}


public boolean accepts( String encoding ) {
    return( encoding.equals( "md5" ) );
}

public String encode( String password ) {
    MD5 hash = new MD5();
    hash.update( password.getBytes() );
    return( digestToString( hash.digest() ) );
}


public boolean authorize( String coded, String password ) {
    return( coded.equals( encode( password ) ) );
}

}
