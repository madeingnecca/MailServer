
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

@(#) $Id: EncoderImpl.java,v 1.3 2002/10/13 11:56:05 aeby Exp $

Revision History:

$Log: EncoderImpl.java,v $
Revision 1.3  2002/10/13 11:56:05  aeby
added MD5 support

Revision 1.2  2001/03/13 16:36:59  aeby
added standard file headers

 
=======================================================================
*/

package com.graeff.pwencode;

public class EncoderImpl {

public boolean accepts( String encoding ) {
    return false;
}


public String encode( String password ) {
    return null;
}


public boolean authorize( String hash, String password ) {
    return encode(password).equals( hash );
}



public String digestToString( byte [] digest )
{
    if (digest == null)
      return "incomplete";

    StringBuffer buf = new StringBuffer();
    int len = digest.length;
    for (int i = 0; i < len; ++i)
      {
        byte b = digest[i];
        byte high = (byte) ((b & 0xff) >>> 4);
        byte low = (byte) (b & 0xf);

        buf.append((char)(high > 9 ? ('a' - 10) + high : '0' + high));
        buf.append((char)(low > 9 ? ('a' - 10) + low : '0' + low));
      }

    return buf.toString();
}



}

