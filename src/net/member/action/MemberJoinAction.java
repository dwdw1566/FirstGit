package net.member.action;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import net.member.db.MemberDAO;
import net.member.db.MemberBean;

public class MemberJoinAction implements Action {
	@Override
	public ActionForward execute(HttpServletRequest request, HttpServletResponse response) throws Exception {
		MemberDAO memberdao = new MemberDAO(); 
		MemberBean memberdata = new MemberBean(); 
		ActionForward forward = new ActionForward(); 
		boolean result = false;
		
		try {
			memberdata.setM_ID(request.getParameter("M_ID"));
			memberdata.setM_NAME(request.getParameter("M_NAME"));
			memberdata.setM_EMAIL(request.getParameter("M_EMAIL"));
			memberdata.setM_PHONE(request.getParameter("M_PHONE"));
			memberdata.setM_PASSWORD(request.getParameter("M_PASSWORD")); 
			
			result = memberdao.memberInsert(memberdata);
			
			if(result == false) {
				return null;
			}
			forward.setRedirect(true);
			forward.setPath("./main.rn");
			return forward;
		}catch(Exception e) {
			e.printStackTrace();
		}
		return null;
	}
}
