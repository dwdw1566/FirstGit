package net.member.db;

public class MemberBean {
	private String M_ID;
	private String M_PASSWORD;
	private String M_NAME;
	private String M_PHONE;
	private String M_EMAIL;
	
	
	public String getM_ID() {
		return M_ID;
	}
	public void setM_ID(String m_ID) {
		M_ID = m_ID;
	}
	public String getM_PASSWORD() {
		return M_PASSWORD;
	}
	public String getM_NAME() {
		return M_NAME;
	}
	public void setM_NAME(String m_NAME) {
		M_NAME = m_NAME;
	}
	public String getM_PHONE() {
		return M_PHONE;
	}
	public void setM_PHONE(String m_PHONE) {
		M_PHONE = m_PHONE;
	}
	public String getM_EMAIL() {
		return M_EMAIL;
	}
	public void setM_EMAIL(String m_EMAIL) {
		M_EMAIL = m_EMAIL;
	}
	public void setM_PASSWORD(String m_PASSWORD) {
		M_PASSWORD = m_PASSWORD;
	}

}
