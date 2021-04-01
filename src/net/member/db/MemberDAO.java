package net.member.db;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

import javax.naming.*;
import javax.sql.*;
import net.member.db.*;

public class MemberDAO {
	Connection conn;
	PreparedStatement pstmt;
	ResultSet rs;

	public MemberDAO() {
		try{
			Context init = new InitialContext();
			DataSource ds = (DataSource) init.lookup("java:comp/env/jdbc/OracleDB");
			conn = ds.getConnection();
		}catch(Exception ex){
			return;
		}
	}
	public MemberBean memberDetail(String id) {
		String sql = "SELECT * FROM MEMBER WHERE M_ID = ?";
		MemberBean member = new MemberBean();
		try {
			System.out.println(member.getM_ID());
			pstmt=conn.prepareStatement(sql);
			pstmt.setString(1, id);
			rs = pstmt.executeQuery();
			
			if(rs.next()) {
				member.setM_PASSWORD(rs.getString(2));
				member.setM_NAME(rs.getString(3));
				member.setM_PHONE(rs.getString(4));
				member.setM_EMAIL(rs.getString(5));
			}
			return member;
		}catch(Exception e) {
			System.out.print("멤버 디테일 에러 : " + e);
			e.printStackTrace();
		}finally {
			if(rs!=null) try {rs.close();}catch(SQLException ex) {}
			if(pstmt!=null) try{pstmt.close();}catch(SQLException ex){}
			if(conn != null) try {conn.close();}catch(SQLException ex) {}
		}
		return null;
	}
	public boolean memberInsert(MemberBean member) {
		String sql = "INSERT INTO MEMBER VALUES(?,?,?,?,?)";
		int result=0;

		try {
			System.out.println(member.getM_ID());
			pstmt=conn.prepareStatement(sql);
			pstmt.setString(1, member.getM_ID());
			pstmt.setString(2, member.getM_NAME());
			pstmt.setString(3, member.getM_EMAIL());
			pstmt.setString(4, member.getM_PHONE());
			pstmt.setString(5, member.getM_PASSWORD());
			result = pstmt.executeUpdate();
			if(result==0) { 
				return false;
			}
			return true;
		}catch(Exception e) {
			e.printStackTrace();
		}finally {
			if(rs!=null) try {rs.close();}catch(SQLException ex) {}
			if(pstmt!=null) try{pstmt.close();}catch(SQLException ex){}
			if(conn != null) try {conn.close();}catch(SQLException ex) {}
		}
		return false;
	}
	public String[] login(String id, String pw) {
		String[] result = new String[2];//길이가 2인 배열이 result 란 변수안에 저장이 될거야 아직은 값은 null
		String sql="select M_ID,M_PASSWORD from MEMBER where M_ID=? and M_PASSWORD=?";
		try {
			pstmt=conn.prepareStatement(sql);
			pstmt.setString(1, id);//id값 가져와서 비교하고 
			//이 밑으로는 필없슴 아니다 rs.next로 데이터 다 가져와서 값 저장해주고 beans에 jsp 에서 get으로 빈즈안에 잇는 값 가져온다면?? 까지가 내생각이야 
			pstmt.setString(2, pw); 
			rs = pstmt.executeQuery();//결괏값 가져오는 쿼리문 이제 rs안에  다들어가있음 데이터 
			if(rs.next()) {
				if(rs.getString(1).equals(id)) {
					if(rs.getString(2).equals(pw)) {
						for(int i=0;i<result.length;i++) {
							result[i] = rs.getString(i+1);
						}
						return result;
					}
					else {
						return null;
					}
				}
				else {
					return null;
				}
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			try {
				rs.close();
				pstmt.close();
				conn.close();
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		return null;
	}
	public boolean memberUpdate(MemberBean member) {//
		String sql="update member set M_NAME=?,M_EMAIL=?,M_PHONE=?,M_PASSWORD=? where M_ID= ?";

		int result=0;
		try {
			pstmt=conn.prepareStatement(sql);
			pstmt.setString(1, member.getM_NAME()); 
			pstmt.setString(2, member.getM_EMAIL());
			pstmt.setString(3, member.getM_PHONE());
			pstmt.setString(4, member.getM_PASSWORD());
			pstmt.setString(5, member.getM_ID());
			result = pstmt.executeUpdate();
			System.out.println("dao:"+result);
			if(result==0) { 
				return false; 
			}
			return true;
		}catch(Exception e) {
			System.out.println("멤버 업데이트 에러:"+e);
			e.printStackTrace();
		}finally {
			if(rs!=null) try {rs.close();}catch(SQLException ex) {}
		if(pstmt!=null) try{pstmt.close();}catch(SQLException ex){}
		if(conn != null) try {conn.close();}catch(SQLException ex) {}
		}
		return false;
	}

}
