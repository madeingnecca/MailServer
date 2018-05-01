
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
E-Mail: aeby@graeff.com

=======================================================================

@(#) $Id: MD5CryptEncoder.java,v 1.1 2004/02/04 08:10:35 aeby Exp $

Revision History:

$Log: MD5CryptEncoder.java,v $
Revision 1.1  2004/02/04 08:10:35  aeby
added MD5Crypt support

Revision 1.2  2001/03/13 16:36:59  aeby
added standard file headers


=======================================================================
*/

package com.graeff.pwencode;

public class MD5CryptEncoder extends EncoderImpl {

public MD5CryptEncoder() {
    Encoder.register( this );
}


public boolean accepts( String encoding ) {
    return( encoding.equals( "md5crypt" ) );
}

public String encode( String password ) {
    String cr = MD5Crypt.crypt( password );
    return cr;
}

public boolean authorize( String coded, String password ) {
    return( coded.equals( MD5Crypt.crypt( password, password ) ) );
}

}
